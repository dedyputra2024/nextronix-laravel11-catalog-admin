<?php

return [
    'rajaongkir' => [
        'api_key' => env('RAJAONGKIR_API_KEY'),
        'base_url' => env('RAJAONGKIR_BASE_URL', 'https://rajaongkir.komerce.id/api/v1'),
        'origin_id' => env('RAJAONGKIR_ORIGIN_ID'),
        'origin_label' => env('STORE_ORIGIN_LABEL', 'Jakarta Pusat, DKI Jakarta'),
        'default_courier' => env('RAJAONGKIR_DEFAULT_COURIER', 'jne'),
    ],
    'default_shipping_cost' => (int) env('STORE_DEFAULT_SHIPPING_COST', 25000),
    'couriers' => [
        'jne' => 'JNE',
        'jnt' => 'J&T Express',
        'sicepat' => 'SiCepat',
        'tiki' => 'TIKI',
        'pos' => 'POS Indonesia',
        'anteraja' => 'AnterAja',
    ],
];
