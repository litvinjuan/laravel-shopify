<?php

namespace Litvinjuan\LaravelShopify\Models;

use Illuminate\Database\Eloquent\Model;
use Litvinjuan\LaravelShopify\Contracts\ShopContract;
use Litvinjuan\LaravelShopify\Traits\ShopTrait;

class Shop extends Model implements ShopContract
{
    use ShopTrait;
}
