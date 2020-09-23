<?php

namespace Litvinjuan\LaravelShopify\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Litvinjuan\LaravelShopify\Contracts\ShopContract;

/**
 * Trait HasShop
 * @package App\Models\Traits
 *
 * @property ShopContract[]|Collection $shops
 */
trait HasShops
{
    public function shops(): HasMany
    {
        return $this->hasMany(config('laravel-shopify.shop-model'));
    }
}
