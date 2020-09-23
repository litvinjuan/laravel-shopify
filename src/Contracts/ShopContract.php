<?php

namespace Litvinjuan\LaravelShopify\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Client\PendingRequest;

interface ShopContract
{
    public function api(): PendingRequest;

    public function getId();

    public function getUser(): Authenticatable;

    public function getDomain(): string;

    public function getNonce(): string;

    public function getAccessToken(): ?string;
}
