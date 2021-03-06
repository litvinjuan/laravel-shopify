<?php

namespace Litvinjuan\LaravelShopify\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Litvinjuan\LaravelShopify\Contracts\ShopContract;

/**
 * @property ShopContract[]|Collection $shops
 */
trait HasShops
{
    public function shops(): HasMany
    {
        return $this->hasMany(config('laravel-shopify.shop-model'));
    }
}
