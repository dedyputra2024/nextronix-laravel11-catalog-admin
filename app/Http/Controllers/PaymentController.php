<?php

namespace App\Http\Controllers;

use App\Mail\OrderPaidMail;
use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Services\MidtransService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class PaymentController extends Controller
{
    public function pay(Order $order, MidtransService $midtrans)
    {
        $this->authorizeOrder($order);

        if (! $order->canBePaid()) {
            return redirect()->route('account.orders.show', $order)->with('error', 'Order ini tidak bisa dibayar melalui Midtrans.');
        }

        if ($order->payment_redirect_url) {
            return view('payment.midtrans', compact('order'));
        }

        try {
            $snap = $midtrans->createSnapTransaction($order);

            $order->update([
                'payment_token' => $snap['token'],
                'payment_redirect_url' => $snap['redirect_url'],
                'payment_status' => 'pending',
            ]);

            return view('payment.midtrans', compact('order'));
        } catch (Throwable $exception) {
            return redirect()->route('account.orders.show', $order)->with('error', $exception->getMessage());
        }
    }

    public function finish(Request $request)
    {
        $order = Order::where('order_number', $request->query('order_id'))->first();
        return $order
            ? redirect()->route('account.orders.show', $order)->with('success', 'Pembayaran selesai diproses. Status final mengikuti notifikasi Midtrans.')
            : redirect()->route('account.orders')->with('success', 'Pembayaran selesai diproses.');
    }

    public function unfinish(Request $request)
    {
        $order = Order::where('order_number', $request->query('order_id'))->first();
        return $order
            ? redirect()->route('account.orders.show', $order)->with('error', 'Pembayaran belum selesai.')
            : redirect()->route('account.orders')->with('error', 'Pembayaran belum selesai.');
    }

    public function error(Request $request)
    {
        $order = Order::where('order_number', $request->query('order_id'))->first();
        return $order
            ? redirect()->route('account.orders.show', $order)->with('error', 'Terjadi error saat pembayaran.')
            : redirect()->route('account.orders')->with('error', 'Terjadi error saat pembayaran.');
    }

    public function notification(Request $request, MidtransService $midtrans, StockService $stockService)
    {
        $payload = $request->all();
        $orderNumber = $payload['order_id'] ?? null;

        abort_unless($orderNumber, 422, 'order_id kosong.');
        abort_unless(isset($payload['signature_key']), 403, 'Signature Midtrans wajib ada.');

        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        $grossAmount = $payload['gross_amount'] ?? number_format((float) $order->total, 2, '.', '');
        $signature = hash('sha512', $orderNumber.($payload['status_code'] ?? '').$grossAmount.config('payment.midtrans.server_key'));

        abort_unless(hash_equals($signature, (string) $payload['signature_key']), 403, 'Signature Midtrans tidak valid.');

        $transactionStatus = (string) ($payload['transaction_status'] ?? '');
        $fraudStatus = $payload['fraud_status'] ?? null;
        $paymentStatus = $midtrans->mapPaymentStatus($transactionStatus, $fraudStatus);
        $wasPaid = $order->isPaid();
        $oldStatus = $order->status;

        DB::transaction(function () use ($order, $payload, $transactionStatus, $fraudStatus, $paymentStatus, $stockService, $oldStatus) {
            $newOrderStatus = match (true) {
                $paymentStatus === 'paid' && $order->status === 'pending' => 'processing',
                $paymentStatus === 'failed' && in_array($order->status, ['pending', 'processing'], true) => 'cancelled',
                default => $order->status,
            };

            $order->update([
                'payment_status' => $paymentStatus,
                'payment_reference' => $payload['transaction_id'] ?? $order->payment_reference,
                'payment_type' => $payload['payment_type'] ?? $order->payment_type,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'paid_at' => $paymentStatus === 'paid' ? now() : $order->paid_at,
                'gateway_payload' => $payload,
                'status' => $newOrderStatus,
            ]);

            PaymentTransaction::create([
                'order_id' => $order->id,
                'gateway' => 'midtrans',
                'order_number' => $order->order_number,
                'transaction_id' => $payload['transaction_id'] ?? null,
                'transaction_status' => $transactionStatus,
                'payment_type' => $payload['payment_type'] ?? null,
                'gross_amount' => $payload['gross_amount'] ?? null,
                'fraud_status' => $fraudStatus,
                'payload' => $payload,
            ]);

            if ($oldStatus !== 'cancelled' && $newOrderStatus === 'cancelled') {
                $stockService->restoreFromOrder($order);
            }
        });

        if (! $wasPaid && $order->fresh()->isPaid()) {
            try {
                Mail::to($order->email)->send(new OrderPaidMail($order->fresh(['items'])));
            } catch (Throwable) {
                // Email failure must not block webhook acknowledgement.
            }
        }

        return response()->json(['ok' => true]);
    }

    private function authorizeOrder(Order $order): void
    {
        abort_unless($order->user_id === auth()->id() || auth()->user()?->is_admin, 403);
    }
}
