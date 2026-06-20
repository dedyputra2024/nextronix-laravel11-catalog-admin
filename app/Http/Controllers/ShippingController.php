<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ShippingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ShippingController extends Controller
{
    public function destinations(Request $request, ShippingService $shipping): JsonResponse
    {
        $validated = $request->validate([
            'search' => ['required', 'string', 'min:2', 'max:100'],
        ]);

        try {
            return response()->json([
                'ok' => true,
                'data' => $shipping->searchDestinations($validated['search'], 10),
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'ok' => false,
                'message' => $exception->getMessage(),
                'data' => [],
            ], 422);
        }
    }

    public function rates(Request $request, ShippingService $shipping): JsonResponse
    {
        $validated = $request->validate([
            'destination_id' => ['required'],
            'courier' => ['required', 'string', 'max:50'],
        ]);

        $weight = $this->cartWeight();

        try {
            return response()->json([
                'ok' => true,
                'weight' => $weight,
                'data' => $shipping->calculateDomesticCost($validated['destination_id'], $validated['courier'], $weight),
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'ok' => false,
                'message' => $exception->getMessage(),
                'data' => [],
            ], 422);
        }
    }

    private function cartWeight(): int
    {
        $cart = collect(session('cart', []));
        $ids = $cart->pluck('product_id')->filter()->values();
        $products = Product::whereIn('id', $ids)->get()->keyBy('id');

        $weight = $cart->sum(function ($item) use ($products) {
            $product = $products->get($item['product_id']);
            return max(1, (int) ($product?->weight_gram ?? 1000)) * (int) $item['quantity'];
        });

        return max(1000, (int) $weight);
    }
}
