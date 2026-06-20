<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    public function items(): Collection
    {
        return collect(session('cart', []))->map(function (array $item) {
            $item['subtotal'] = (float) $item['price'] * (int) $item['quantity'];
            return $item;
        });
    }

    public function subtotal(): float
    {
        return (float) $this->items()->sum('subtotal');
    }

    public function weight(): int
    {
        $cart = collect(session('cart', []));
        $ids = $cart->pluck('product_id')->filter()->values();
        $products = Product::whereIn('id', $ids)->get()->keyBy('id');

        $weight = $cart->sum(function (array $item) use ($products) {
            $product = $products->get($item['product_id']);
            return max(1, (int) ($product?->weight_gram ?? 1000)) * (int) $item['quantity'];
        });

        return max(1000, (int) $weight);
    }
}
