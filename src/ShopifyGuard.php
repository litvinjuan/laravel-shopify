<?php

namespace Litvinjuan\LaravelShopify;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Litvinjuan\LaravelShopify\Facades\Shopify;
use Litvinjuan\LaravelShopify\Scopes\ConnectedShopScope;

class ShopifyGuard implements Guard
{
    use GuardHelpers;

    /** @var Request */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function user()
    {
        if ($this->hasUser()) {
            return $this->user;
        }

        if (! Shopify::isValidHmac()) {
            return abort(403);
        }

        if ($shop = $this->getShop()) {
            Shopify::setShop($shop);
            $this->user = $shop->user;
        }

        return $this->user;
    }

    public function validate(array $credentials = [])
    {
        return false;
    }

    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
    }

    private function getShop()
    {
        $shopQuery = $this->getShopClass()::query()->domain($this->request->get('shop'));

        if (request()->routeIs('shopify.callback')) {
            $shopQuery->withoutGlobalScope(ConnectedShopScope::class);
        }

        return $shopQuery->first();
    }

    private function getShopClass()
    {
        return config('laravel-shopify.shop-model');
    }
}
