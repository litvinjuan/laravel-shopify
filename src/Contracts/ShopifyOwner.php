<?php

namespace Litvinjuan\LaravelShopify\Contracts;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Interface ShopOwner
 * @package App\Contracts
 *
 * @property Shop $shop
 */
interface ShopifyOwner
{
    public function getKey();

    public function shop(): HasOne;
}
