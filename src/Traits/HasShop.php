<?php

namespace Litvinjuan\LaravelShopify\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Litvinjuan\LaravelShopify\Models\Shop;

/**
 * Trait HasShop
 * @package App\Models\Traits
 *
 * @property Shop $shop
 */
trait HasShop
{
    public function shop(): HasOne
    {
        return $this->hasOne(Shop::class);
    }
}
