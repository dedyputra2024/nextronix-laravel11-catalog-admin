<?php

namespace App\Mail;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function build(): self
    {
        $this->order->loadMissing('items');
        $pdf = Pdf::loadView('invoices.order', ['order' => $this->order])->output();

        return $this->subject('Invoice Pesanan '.$this->order->order_number)
            ->view('emails.order-created')
            ->attachData($pdf, 'invoice-'.$this->order->order_number.'.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
