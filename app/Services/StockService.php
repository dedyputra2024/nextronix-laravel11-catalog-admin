<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use RuntimeException;

class StockService
{
    public function ensureAvailable(iterable $cartItems): void
    {
        foreach ($cartItems as $item) {
            $product = Product::lockForUpdate()->find($item['product_id']);

            if (! $product || ! $product->is_active) {
                throw new RuntimeException('Salah satu produk sudah tidak tersedia.');
            }

            if ($product->stock < (int) $item['quantity']) {
                throw new RuntimeException("Stok {$product->name} tidak mencukupi.");
            }
        }
    }

    public function reserveFromCart(iterable $cartItems): void
    {
        foreach ($cartItems as $item) {
            Product::lockForUpdate()
                ->findOrFail($item['product_id'])
                ->decrement('stock', (int) $item['quantity']);
        }
    }

    public function restoreFromOrder(Order $order): void
    {
        $order->loadMissing('items');

        foreach ($order->items as $item) {
            if ($item->product_id) {
                Product::whereKey($item->product_id)->increment('stock', (int) $item->quantity);
            }
        }
    }
}
