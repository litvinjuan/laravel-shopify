<?php

namespace Litvinjuan\LaravelShopify\Contracts;

use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property ShopContract $shop
 */
interface ShopifyOwner
{
    public function getKey();

    public function shop(): HasOne;
}
