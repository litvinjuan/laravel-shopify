<?php

namespace Litvinjuan\LaravelShopify\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Litvinjuan\LaravelShopify\Exceptions\ShopifyException;
use Litvinjuan\LaravelShopify\Facades\Shopify;

class ValidateShopifySignature
{
    public function handle(Request $request, Closure $next)
    {
        if (! Shopify::isValidHmac()) {
            return abort(403);
        }

        if (! $this->hasShop($request)) {
            return abort(403);
        }

        $shop = $this->getShop($request);

        try {
            Shopify::assertShopExists($shop);
        } catch (ShopifyException $exception) {
            return abort(404); // Webhook will auto-delete after 20 failed attempts
        }

        Shopify::setShop($shop);

        return $next($request);
    }

    private function hasShop(Request $request)
    {
        return $request->hasHeader('x-shopify-shop-domain') || $request->has('shop');
    }

    private function getShop(Request $request)
    {
        return $request->header('x-shopify-shop-domain') ?? $request->get('shop');
    }
}
