<?php

namespace Litvinjuan\LaravelShopify\Exceptions;

class ShopifyException extends \Exception
{
    public static function missingCallbackParameters(): self
    {
        return new static('Invalid callback parameters');
    }

    public static function invalidCallbackNonce(): self
    {
        return new static('Invalid callback nonce');
    }

    public static function invalidHmac(): self
    {
        return new static('Invalid shopify signature');
    }

    public static function accessTokenFailed(): self
    {
        return new static('There was an error connecting your account');
    }

    public static function domainTaken($domain): self
    {
        return new static("We couldn't connect you to {$domain}. The store is already associated with another account.");
    }

    public static function shopNotFound($domain): self
    {
        return new static("We couldn't find a shop corresponding to {$domain}.");
    }
}
