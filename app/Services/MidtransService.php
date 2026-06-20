<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = (string) config('payment.midtrans.server_key');
        Config::$isProduction = (bool) config('payment.midtrans.is_production');
        Config::$isSanitized = (bool) config('payment.midtrans.is_sanitized');
        Config::$is3ds = (bool) config('payment.midtrans.is_3ds');
        Config::$overrideNotifUrl = route('payment.midtrans.notification');
    }

    public function createSnapTransaction(Order $order): array
    {
        if (! config('payment.midtrans.server_key')) {
            throw new \RuntimeException('MIDTRANS_SERVER_KEY belum diisi di file .env.');
        }

        $order->loadMissing('items');

        $itemDetails = $order->items->map(fn ($item) => [
            'id' => (string) ($item->product_id ?: $item->id),
            'price' => (int) round($item->price),
            'quantity' => (int) $item->quantity,
            'name' => str($item->product_name)->limit(48, '')->toString(),
        ])->values()->all();

        if ((float) $order->shipping_cost > 0) {
            $itemDetails[] = [
                'id' => 'SHIPPING',
                'price' => (int) round($order->shipping_cost),
                'quantity' => 1,
                'name' => 'Ongkir '.$order->courier_code.' '.$order->courier_service,
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int) round($order->total),
            ],
            'customer_details' => [
                'first_name' => $order->name,
                'email' => $order->email,
                'phone' => $order->phone,
                'shipping_address' => [
                    'first_name' => $order->name,
                    'phone' => $order->phone,
                    'address' => $order->address,
                    'city' => $order->city,
                    'postal_code' => $order->postal_code,
                    'country_code' => 'IDN',
                ],
            ],
            'item_details' => $itemDetails,
            'callbacks' => [
                'finish' => route('payment.midtrans.finish'),
                'unfinish' => route('payment.midtrans.unfinish'),
                'error' => route('payment.midtrans.error'),
            ],
        ];

        $transaction = Snap::createTransaction($params);

        return [
            'token' => $transaction->token,
            'redirect_url' => $transaction->redirect_url,
        ];
    }

    public function mapPaymentStatus(string $transactionStatus, ?string $fraudStatus = null): string
    {
        return match ($transactionStatus) {
            'capture' => $fraudStatus === 'challenge' ? 'challenge' : 'paid',
            'settlement' => 'paid',
            'pending' => 'pending',
            'deny', 'cancel', 'expire', 'failure' => 'failed',
            'refund', 'partial_refund' => 'refunded',
            default => 'unpaid',
        };
    }
}
