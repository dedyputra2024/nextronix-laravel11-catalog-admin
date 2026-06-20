<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ShippingService
{
    public function searchDestinations(string $search, int $limit = 10): array
    {
        $this->ensureConfigured();
        $search = trim($search);

        return Cache::remember('rajaongkir_destination_'.md5($search.$limit), now()->addHours(12), function () use ($search, $limit) {
            $response = Http::withHeaders($this->headers())
                ->timeout(20)
                ->get($this->baseUrl().'/destination/domestic-destination', [
                    'search' => $search,
                    'limit' => $limit,
                    'offset' => 0,
                ]);

            if (! $response->successful()) {
                throw new \RuntimeException('Gagal mengambil destination RajaOngkir: '.$response->body());
            }

            return $response->json('data') ?? [];
        });
    }

    public function calculateDomesticCost(int|string $destinationId, string $courier, int $weight, string $price = 'lowest'): array
    {
        $this->ensureConfigured();

        if ($weight <= 0) {
            $weight = 1000;
        }

        $response = Http::asForm()
            ->withHeaders($this->headers())
            ->timeout(30)
            ->post($this->baseUrl().'/calculate/domestic-cost', [
                'origin' => (int) config('shipping.rajaongkir.origin_id'),
                'destination' => (int) $destinationId,
                'weight' => $weight,
                'courier' => $courier,
                'price' => $price,
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException('Gagal menghitung ongkir RajaOngkir: '.$response->body());
        }

        return $response->json('data') ?? [];
    }

    public function defaultShippingCost(float $subtotal): int
    {
        return $subtotal >= 3000000 ? 0 : (int) config('shipping.default_shipping_cost', 25000);
    }

    private function ensureConfigured(): void
    {
        if (! config('shipping.rajaongkir.api_key')) {
            throw new \RuntimeException('RAJAONGKIR_API_KEY belum diisi di file .env.');
        }

        if (! config('shipping.rajaongkir.origin_id')) {
            throw new \RuntimeException('RAJAONGKIR_ORIGIN_ID belum diisi di file .env.');
        }
    }

    private function baseUrl(): string
    {
        return rtrim((string) config('shipping.rajaongkir.base_url'), '/');
    }

    private function headers(): array
    {
        return [
            'key' => (string) config('shipping.rajaongkir.api_key'),
            'Accept' => 'application/json',
        ];
    }
}
