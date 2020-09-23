<?php

namespace litvinjuan\LaravelShopify;

use Illuminate\Support\Facades\Facade;

/**
 * @see \litvinjuan\LaravelShopify\LaravelShopify
 */
class LaravelShopifyFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-shopify';
    }
}
