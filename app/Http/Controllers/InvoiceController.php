<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function download(Order $order)
    {
        abort_unless($order->user_id === auth()->id() || auth()->user()?->is_admin, 403);

        $order->loadMissing('items.product', 'user');

        return Pdf::loadView('invoices.order', compact('order'))
            ->setPaper('a4')
            ->download('invoice-'.$order->order_number.'.pdf');
    }
}
