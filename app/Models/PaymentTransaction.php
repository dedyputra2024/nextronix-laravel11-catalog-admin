<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'gateway',
        'order_number',
        'transaction_id',
        'transaction_status',
        'payment_type',
        'gross_amount',
        'fraud_status',
        'payload',
    ];

    protected function casts(): array
    {
        return [
            'gross_amount' => 'decimal:2',
            'payload' => 'array',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
