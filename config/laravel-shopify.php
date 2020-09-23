<?php

return [
    'api-key' => env('SHOPIFY_API_KEY', ''),
    'api-secret' => env('SHOPIFY_API_SECRET', ''),
    'webhook-secret' => env('SHOPIFY_WEBHOOK_SECRET', ''),
    'scopes' => env('SHOPIFY_API_SCOPES', 'read_products'),
    'redirect-url' => env('SHOPIFY_API_REDIRECT', 'http://localhost/shopify/callback'),
    'user-model' => App\Models\User::class,
    'shop-model' => App\Models\Shop::class,
];
