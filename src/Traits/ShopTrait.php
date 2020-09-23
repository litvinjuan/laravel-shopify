<?php

namespace Litvinjuan\LaravelShopify\Traits;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Litvinjuan\LaravelShopify\Contracts\ShopContract;
use Litvinjuan\LaravelShopify\Scopes\ConnectedShopScope;

/**
 * @property int $id
 * @property int $user_id
 * @property Authenticatable $user
 * @property string $domain
 * @property string $nonce
 * @property string|null $access_token
 * @method static Builder|ShopContract domain($domain)
 * @method static Builder|ShopContract connected()
 */
trait ShopTrait
{
    protected static function bootShopTrait()
    {
        static::addGlobalScope(new ConnectedShopScope);
    }

    public function getFillable()
    {
        return array_merge([
            'user_id',
            'domain',
            'access_token',
            'nonce',
        ], $this->fillable);
    }

    public function user()
    {
        return $this->belongsTo(config('laravel-shopify.user-model'));
    }

    public function scopeDomain(Builder $query, $domain)
    {
        $query->where('domain', $domain);
    }

    public function scopeConnected(Builder $query)
    {
        $query->whereNotNull('access_token');
    }

    public function api(): PendingRequest
    {
        return Http::baseUrl("https://{$this->domain}/")
            ->withHeaders([
                'X-Shopify-Access-Token' => $this->access_token,
            ]);
    }
}
