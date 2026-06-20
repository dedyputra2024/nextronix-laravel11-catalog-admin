<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('cart', [
            'cartItems' => $this->cartItems(),
            'subtotal' => $this->subtotal(),
        ]);
    }

    public function add(Request $request, Product $product)
    {
        abort_unless($product->is_active, 404);

        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $quantity = (int) ($validated['quantity'] ?? 1);
        $cart = session('cart', []);
        $currentQuantity = $cart[$product->id]['quantity'] ?? 0;

        if ($currentQuantity + $quantity > $product->stock) {
            return back()->with('error', 'Jumlah melebihi stok produk yang tersedia.');
        }

        $cart[$product->id] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'image' => $product->image,
            'image_url' => $product->image_url,
            'price' => $product->active_price,
            'quantity' => $currentQuantity + $quantity,
        ];

        session(['cart' => $cart]);

        return back()->with('success', 'Produk berhasil ditambahkan ke cart.');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = session('cart', []);

        if (! isset($cart[$product->id])) {
            return back()->with('error', 'Produk tidak ditemukan di cart.');
        }

        if ((int) $validated['quantity'] > $product->stock) {
            return back()->with('error', 'Jumlah melebihi stok produk yang tersedia.');
        }

        $cart[$product->id]['quantity'] = (int) $validated['quantity'];
        session(['cart' => $cart]);

        return back()->with('success', 'Cart berhasil diperbarui.');
    }

    public function remove(Product $product)
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);

        return back()->with('success', 'Produk berhasil dihapus dari cart.');
    }

    public function clear()
    {
        session()->forget('cart');

        return back()->with('success', 'Cart berhasil dikosongkan.');
    }

    private function cartItems()
    {
        return collect(session('cart', []))->map(function ($item) {
            $item['subtotal'] = $item['price'] * $item['quantity'];
            return $item;
        });
    }

    private function subtotal(): float
    {
        return (float) $this->cartItems()->sum('subtotal');
    }
}
