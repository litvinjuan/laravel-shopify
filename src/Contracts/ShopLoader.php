<?php

namespace Litvinjuan\LaravelShopify\Contracts;

interface ShopLoader
{
    public function load(): ?ShopContract;
}
