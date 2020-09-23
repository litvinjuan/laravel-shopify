<?php

namespace Litvinjuan\LaravelShopify\Contracts;

use Illuminate\Http\Client\PendingRequest;

interface ShopContract
{
    public function api(): PendingRequest;
}
