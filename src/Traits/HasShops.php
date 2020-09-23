<?php

namespace Litvinjuan\LaravelShopify\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Litvinjuan\LaravelShopify\Models\Shop;

/**
 * Trait HasShop
 * @package App\Models\Traits
 *
 * @property Shop[]|Collection $shops
 */
trait HasShops
{
    public function shops(): HasMany
    {
        return $this->hasMany(Shop::class);
    }
}
