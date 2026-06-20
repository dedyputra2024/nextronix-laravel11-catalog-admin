<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutService
{
    public function __construct(private readonly StockService $stockService)
    {
    }

    public function createOrder(int $userId, array $data, iterable $cartItems, float $subtotal, float $shippingCost, float $total, int $shippingWeight): Order
    {
        return DB::transaction(function () use ($userId, $data, $cartItems, $subtotal, $shippingCost, $total, $shippingWeight) {
            $this->stockService->ensureAvailable($cartItems);

            $paymentStatus = $data['payment_method'] === 'cod' ? 'cod_pending' : 'unpaid';

            $order = Order::create([
                'user_id' => $userId,
                'order_number' => 'ELC-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'city' => $data['city'],
                'postal_code' => $data['postal_code'],
                'destination_id' => $data['destination_id'] ?? null,
                'destination_label' => $data['destination_label'] ?? null,
                'shipping_weight' => $shippingWeight,
                'courier_code' => $data['courier_code'] ?? null,
                'courier_name' => $data['courier_name'] ?? null,
                'courier_service' => $data['courier_service'] ?? null,
                'courier_etd' => $data['courier_etd'] ?? null,
                'payment_method' => $data['payment_method'],
                'payment_status' => $paymentStatus,
                'notes' => $data['notes'] ?? null,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'status' => 'pending',
            ]);

            foreach ($cartItems as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => (int) $item['quantity'],
                    'price' => (float) $item['price'],
                    'subtotal' => (float) $item['subtotal'],
                ]);
            }

            $this->stockService->reserveFromCart($cartItems);

            return $order;
        });
    }
}
