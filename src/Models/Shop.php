<?php

namespace Litvinjuan\LaravelShopify\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Litvinjuan\LaravelShopify\Contracts\ShopContract;
use Litvinjuan\LaravelShopify\Database\Factories\ShopFactory;
use Litvinjuan\LaravelShopify\Traits\ShopTrait;

class Shop extends Model implements ShopContract
{
    use HasFactory;
    use ShopTrait;

    protected static function newFactory()
    {
        return ShopFactory::new();
    }
}
