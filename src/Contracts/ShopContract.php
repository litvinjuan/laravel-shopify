<?php

namespace Litvinjuan\LaravelShopify\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Client\PendingRequest;

/**
 * Interface ShopContract
 * @package Litvinjuan\LaravelShopify\Contracts
 *
 * @property int $id
 * @property int $user_id
 * @property Authenticatable $user
 * @property string $domain
 * @property string $nonce
 * @property string|null $access_token
 * @method static Builder|ShopContract domain($domain)
 * @method static Builder|ShopContract connected()
 */
interface ShopContract
{
    public function api(): PendingRequest;
}
