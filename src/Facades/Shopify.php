<?php

namespace Litvinjuan\LaravelShopify\Facades;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;
use Litvinjuan\LaravelShopify\Contracts\ShopContract;
use Litvinjuan\LaravelShopify\Contracts\ShopifyOwner;
use Litvinjuan\LaravelShopify\LaravelShopifyManager;

/**
 * @method static void setShop($shop)
 * @method static ShopContract|null getShop()
 * @method static RedirectResponse redirect(ShopifyOwner $owner, $domain, $callbackUrl = null, $scopes = null)
 * @method static ShopContract callback(ShopifyOwner $owner, Request $request)
 * @method static void forget()
 * @method static bool hasShop()
 * @method static bool isValidHmac()
 * @method static void assertShopExists($domain)
 */
class Shopify extends Facade
{
    protected static function getFacadeAccessor()
    {
        return LaravelShopifyManager::class;
    }
}
