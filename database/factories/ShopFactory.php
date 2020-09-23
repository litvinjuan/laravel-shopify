<?php

namespace Litvinjuan\LaravelShopify\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ShopFactory extends Factory
{
    public function modelName()
    {
        return config('laravel-shopify.shop-model');
    }

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
