<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderStatusRequest;
use App\Mail\OrderStatusUpdatedMail;
use App\Models\Order;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        if ($request->filled('q')) {
            $keyword = $request->string('q')->toString();
            $query->where(function ($subQuery) use ($keyword) {
                $subQuery->where('order_number', 'like', "%{$keyword}%")
                    ->orWhere('name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('phone', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->paginate(12)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'items.product', 'paymentTransactions');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(OrderStatusRequest $request, Order $order, StockService $stockService)
    {
        $validated = $request->validated();
        $oldStatus = $order->status;

        DB::transaction(function () use ($order, $validated, $oldStatus, $stockService) {
            $order->update($validated);

            if ($oldStatus !== 'cancelled' && $order->status === 'cancelled') {
                $stockService->restoreFromOrder($order);
            }
        });

        if ($oldStatus !== $order->status) {
            try {
                Mail::to($order->email)->send(new OrderStatusUpdatedMail($order->fresh(['items'])));
            } catch (Throwable) {
                // Email failure should not block admin update.
            }
        }

        return back()->with('success', 'Status order berhasil diperbarui. Stok otomatis dikembalikan jika order dibatalkan.');
    }
}
