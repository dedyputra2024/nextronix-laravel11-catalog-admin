<?php

namespace App\Http\Controllers;

use App\Models\Order;

class AccountController extends Controller
{
    public function orders()
    {
        $orders = auth()->user()
            ->orders()
            ->latest()
            ->paginate(10);

        return view('account.orders', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        abort_unless($order->user_id === auth()->id() || auth()->user()->is_admin, 403);

        $order->load('items.product', 'paymentTransactions');

        return view('account.order-show', compact('order'));
    }
}
