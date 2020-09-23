<?php

namespace Litvinjuan\LaravelShopify\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Litvinjuan\LaravelShopify\Contracts\ShopContract;

/**
 * Trait HasShop
 * @package App\Models\Traits
 *
 * @property ShopContract $shop
 */
trait HasShop
{
    public function shop(): HasOne
    {
        return $this->hasOne(config('laravel-shopify.shop-model'));
    }
}
