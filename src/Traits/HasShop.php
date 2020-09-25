<?php

namespace Litvinjuan\LaravelShopify\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Litvinjuan\LaravelShopify\Contracts\ShopContract;

/**
 * @property ShopContract $shop
 */
trait HasShop
{
    public function shop(): HasOne
    {
        return $this->hasOne(config('laravel-shopify.shop-model'));
    }
}
