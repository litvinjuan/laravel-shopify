<?php

namespace Litvinjuan\LaravelShopify;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Litvinjuan\LaravelShopify\Contracts\ShopContract;
use Litvinjuan\LaravelShopify\Contracts\ShopifyOwner;
use Litvinjuan\LaravelShopify\Contracts\ShopLoader;
use Litvinjuan\LaravelShopify\Exceptions\ShopifyException;
use Litvinjuan\LaravelShopify\Models\Shop;

class LaravelShopifyManager
{
    const PARAMETERS = ['state', 'shop', 'hmac', 'code', 'timestamp'];

    private $shop;

    /** @var array */
    private $callbackData;

    public function redirect(ShopifyOwner $owner, $domain, $callbackUrl = null, $scopes = null): RedirectResponse
    {
        $this->assertDomainNotTaken($owner, $domain);

        $nonce = Str::random(64);
        $url = $this->buildRedirectUrl($domain, $callbackUrl, $scopes, $nonce);

        $this->createShop($owner, $domain, $nonce);

        return Redirect::to($url);
    }

    public function callback(ShopifyOwner $owner, Request $request): ?ShopContract
    {
        $this->assertValidHmac();

        if (! $request->has(self::PARAMETERS)) {
            throw ShopifyException::missingCallbackParameters();
        }

        $this->callbackData = $request->only(self::PARAMETERS);

        $this->assertShopExists($this->callbackData['shop']);
        $this->loadCallbackShop($owner);

        $this->assertValidNonce();

        $this->generateAccessCode();

        return $this->getShop();
    }

    public function forget(): void
    {
        $this->shop = null;
    }

    public function hasShop(): bool
    {
        return ! is_null($this->getShop());
    }

    public function getShop(): ?ShopContract
    {
        if (! $this->shop) {
            /** @var ShopLoader $loader */
            $loaderClass = config('laravel-shopify.shop-loader-class');
            if ($loaderClass) {
                $loader = new $loaderClass();
                $this->shop = $loader->load();
            }
        }

        return $this->shop;
    }

    public function setShop($shop): void
    {
        if (is_string($shop)) {
            $shop = $this->getShopClass()::query()->domain($shop)->connected()->firstOrFail();
        }

        $this->shop = $shop;
    }

    private function assertDomainNotTaken(ShopifyOwner $owner, $domain): void
    {
        // Check the shop isn't owned by another user
        $shopWithSameDomain = $this->getShopClass()::query()
            ->where('user_id', '!=', $owner->getKey())
            ->domain($domain);

        if ($shopWithSameDomain->exists()) {
            throw ShopifyException::domainTaken($domain);
        }
    }

    public function assertShopExists($domain): void
    {
        $shopQuery = $this->getShopClass()::query()->onlyDisconnected()->domain($domain);

        if ($shopQuery->doesntExist()) {
            throw ShopifyException::shopNotFound($domain);
        }
    }

    private function loadCallbackShop(ShopifyOwner $owner): void
    {
        /** @var Shop $shop */
        $shop = $owner
            ->shop()
            ->onlyDisconnected()
            ->domain($this->callbackData['shop'])
            ->first();

        $this->setShop($shop);
    }

    private function assertValidNonce(): void
    {
        if ($this->callbackData['state'] != optional($this->getShop())->nonce) {
            throw ShopifyException::invalidCallbackNonce();
        }
    }

    private function assertValidHmac(): void
    {
        if (! $this->isValidHmac()) {
            throw ShopifyException::invalidHmac();
        }
    }

    public function isValidHmac(): bool
    {
        if ($this->isWebhookRequest()) {
            return $this->validateWebhookHmac();
        }

        return $this->validateRequestHmac();
    }

    private function isWebhookRequest(): bool
    {
        return request()->routeIs('webhooks.*');
    }

    private function validateRequestHmac(): bool
    {
        if (! request()->has('hmac')) {
            return false;
        }

        return hash_equals(request()->get('hmac'), $this->requestHmac());
    }

    private function validateWebhookHmac(): bool
    {
        if (! request()->hasHeader('x-shopify-hmac-sha256')) {
            return false;
        }

        return
            hash_equals(request()->header('x-shopify-hmac-sha256'), $this->webhookHmac($this->webhookSecret())) ||
            hash_equals(request()->header('x-shopify-hmac-sha256'), $this->webhookHmac($this->apiSecret()));
    }

    private function requestHmac(): string
    {
        // Convert query string into array
        parse_str(request()->getQueryString(), $parameters);

        // Remove hmac parameter
        unset($parameters['hmac']);

        // Convert array back to a query string
        $filtered = http_build_query($parameters);

        // Build signature from the filtered query string using the api secret as the signature
        return hash_hmac('sha256', $filtered, $this->apiSecret());
    }

    private function webhookHmac($secret): string
    {
        $hash = hash_hmac('sha256', request()->getContent(), $secret, true);

        return base64_encode($hash);
    }

    private function generateAccessCode(): void
    {
        $response = Http::post("https://{$this->getShop()->domain}/admin/oauth/access_token", [
            'client_id' => config('laravel-shopify.api-key'),
            'client_secret' => $this->apiSecret(),
            'code' => $this->callbackData['code'],
        ]);

        if ($response->failed()) {
            throw ShopifyException::accessTokenFailed();
        }

        $data = json_decode($response->body(), true);

        $this->setAccessToken($data['access_token']);
    }

    private function setAccessToken($accessToken): void
    {
        /** @var Model|ShopContract $shop */
        $shop = $this->getShop();

        if ($shop) {
            $shop->forceFill([
                'access_token' => $accessToken,
            ])->save();
        }
    }

    private function buildRedirectUrl($shopDomain, $callbackUrl, $scopes, $nonce): string
    {
        $apiKey = config('laravel-shopify.api-key');
        $callbackUrl = $callbackUrl ?? config('laravel-shopify.callback-url');

        if (! $scopes) {
            $scopes = config('laravel-shopify.scopes');
        }

        if (is_array($scopes)) {
            $scopes = implode(',', $scopes);
        }

        return "https://{$shopDomain}/admin/oauth/authorize?client_id={$apiKey}&scope={$scopes}&redirect_uri={$callbackUrl}&state={$nonce}";
    }

    private function createShop(ShopifyOwner $owner, $domain, $nonce)
    {
        // Find the user's shop (including disconnected) or create a new one
        $shop = $owner
            ->shop()
            ->withDisconnected()
            ->firstOrNew();

        // Set shop's nonce and domain, clear old access token if any
        $shop->forceFill([
            'nonce' => $nonce,
            'domain' => $domain,
            'access_token' => null,
        ])->save();
    }

    private function getShopClass()
    {
        return config('laravel-shopify.shop-model');
    }

    private function webhookSecret()
    {
        return config('laravel-shopify.webhook-secret');
    }

    private function apiSecret()
    {
        return config('laravel-shopify.api-secret');
    }
}
