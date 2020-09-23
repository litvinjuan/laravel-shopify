<?php

namespace Litvinjuan\LaravelShopify\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Litvinjuan\LaravelShopify\Contracts\ShopContract;
use Litvinjuan\LaravelShopify\Database\Factories\ShopFactory;
use Litvinjuan\LaravelShopify\Scopes\ConnectedShopScope;

/**
 * App\Models\Shop
 *
 * @property int $id
 * @property int $user_id
 * @property Authenticatable $user
 * @property string $domain
 * @property string $nonce
 * @property string|null $access_token
 * @method static Builder|Shop domain($domain)
 * @method static Builder|Shop connected()
 */
class Shop extends Model implements ShopContract
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ConnectedShopScope);
    }

    protected $fillable = [
        'user_id',
        'domain',
        'access_token',
        'nonce',
    ];

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

    protected static function newFactory()
    {
        // Use app factory. If missing, use package factory
        return Factory::factoryForModel(get_called_class()) ?: ShopFactory::new();
    }
}
