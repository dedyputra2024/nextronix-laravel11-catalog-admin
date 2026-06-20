<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'name',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'destination_id',
        'destination_label',
        'shipping_weight',
        'courier_code',
        'courier_name',
        'courier_service',
        'courier_etd',
        'payment_method',
        'payment_status',
        'payment_token',
        'payment_redirect_url',
        'payment_reference',
        'payment_type',
        'transaction_status',
        'fraud_status',
        'paid_at',
        'gateway_payload',
        'notes',
        'subtotal',
        'shipping_cost',
        'total',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'total' => 'decimal:2',
            'shipping_weight' => 'integer',
            'paid_at' => 'datetime',
            'gateway_payload' => 'array',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'order_number';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function canBePaid(): bool
    {
        return $this->payment_method === 'midtrans' && ! $this->isPaid() && ! in_array($this->status, ['cancelled', 'completed'], true);
    }
}
