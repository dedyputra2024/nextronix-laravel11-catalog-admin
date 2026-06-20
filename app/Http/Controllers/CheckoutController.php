<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Mail\OrderCreatedMail;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\ShippingService;
use Illuminate\Support\Facades\Mail;
use Throwable;

class CheckoutController extends Controller
{
    public function show(ShippingService $shipping, CartService $cart)
    {
        $cartItems = $cart->items();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Cart masih kosong. Tambahkan produk dulu sebelum checkout.');
        }

        $subtotal = $cart->subtotal();
        $shippingWeight = $cart->weight();
        $shippingCost = $shipping->defaultShippingCost($subtotal);
        $total = $subtotal + $shippingCost;
        $couriers = config('shipping.couriers');

        return view('checkout', compact('cartItems', 'subtotal', 'shippingCost', 'total', 'shippingWeight', 'couriers'));
    }

    public function store(CheckoutRequest $request, ShippingService $shipping, CartService $cart, CheckoutService $checkout)
    {
        $cartItems = $cart->items();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Cart masih kosong.');
        }

        $validated = $request->validated();
        $subtotal = $cart->subtotal();
        $shippingWeight = $cart->weight();
        $shippingCost = $this->resolveShippingCost($validated, $shipping, $shippingWeight, $subtotal);
        $total = $subtotal + $shippingCost;

        try {
            $order = $checkout->createOrder(auth()->id(), $validated, $cartItems, $subtotal, $shippingCost, $total, $shippingWeight);
        } catch (Throwable $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }

        session()->forget('cart');

        try {
            Mail::to($order->email)->send(new OrderCreatedMail($order->load('items')));
        } catch (Throwable) {
            // Pada mode demo MAIL_MAILER=log. Gagal email tidak membatalkan order.
        }

        if ($order->payment_method === 'midtrans') {
            return redirect()->route('payment.midtrans.pay', $order)->with('success', 'Order berhasil dibuat. Lanjutkan pembayaran Midtrans.');
        }

        return redirect()->route('account.orders.show', $order)->with('success', 'Checkout berhasil. Order sudah masuk database dan invoice dikirim/logged.');
    }

    private function resolveShippingCost(array $validated, ShippingService $shipping, int $weight, float $subtotal): float
    {
        if (empty($validated['destination_id']) || empty($validated['courier_code']) || empty($validated['courier_service'])) {
            return $shipping->defaultShippingCost($subtotal);
        }

        try {
            $rates = collect($shipping->calculateDomesticCost($validated['destination_id'], $validated['courier_code'], $weight));
            $selected = $rates->first(function ($rate) use ($validated) {
                return strtolower((string) ($rate['service'] ?? '')) === strtolower($validated['courier_service']);
            });

            if ($selected) {
                return (float) ($selected['cost'] ?? 0);
            }
        } catch (Throwable) {
            // Kalau API tidak dikonfigurasi saat demo lokal, gunakan ongkir dari form/default.
        }

        return (float) ($validated['shipping_cost'] ?? $shipping->defaultShippingCost($subtotal));
    }
}
