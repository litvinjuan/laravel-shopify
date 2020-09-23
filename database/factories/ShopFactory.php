<?php

namespace Litvinjuan\LaravelShopify\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Litvinjuan\LaravelShopify\Models\Shop;

class ShopFactory extends Factory
{
    protected $model = Shop::class;

    public function definition()
    {
        return [
            'user_id' => config('laravel-shopify.user-model')::factory(),
            'domain' => $this->faker->slug(2) . '.myshopify.com',
            'access_token' => Str::random(32),
            'nonce' => Str::random(64),
        ];
    }
}
