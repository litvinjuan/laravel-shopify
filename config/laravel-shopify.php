<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Api Credentials
    |--------------------------------------------------------------------------
    |
    | These are the credentials to use to connect to Shopify and are unique
    | to your app. To generate an Api Key and Secret, create a developer
    | account in Shopify, create a new App, and copy the credentials displayed
    | on screen.
    |
    */

    'api-key' => env('SHOPIFY_API_KEY', ''),

    'api-secret' => env('SHOPIFY_API_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    |
    | The default scopes to use when requesting access to a Shopify store.
    | If no scopes are provided in the redirect step, these scopes are used
    |
    */

    'scopes' => env('SHOPIFY_API_SCOPES', 'read_products,write_products'),

    /*
    |--------------------------------------------------------------------------
    | Callback Url
    |--------------------------------------------------------------------------
    |
    | The default url where the user should be redirected for the Shopify
    | authentication callback. If no callback url is provided in the
    | redirect step, this url is used
    |
    */

    'callback-url' => env('SHOPIFY_API_CALLBACK_URL', 'http://localhost/shopify/callback'),

    /*
    |--------------------------------------------------------------------------
    | Webhook secret
    |--------------------------------------------------------------------------
    |
    | This secret is used to verify the signature of Shopify webhooks that
    | are set up within a store's admin panel. This secret can be found
    | at https://[your-store-url]/admin/settings/notifications
    |
    | This is not used for webhooks you set up from your app, and should
    | only be used for webhooks created from the notifications link
    | provided above. For most apps, this parameter won't be used.
    |
    */

    'webhook-secret' => env('SHOPIFY_WEBHOOK_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | This is the model to use as the User. This model should implement
    | the ShopifyOwner interface and be an Authenticatable model
    |
    */

    'user-model' => App\Models\User::class,

    /*
    |--------------------------------------------------------------------------
    | Shop Model
    |--------------------------------------------------------------------------
    |
    | This is the model to use as the Shop. If the default model works for
    | your app, leave it as is. If you want to include your own attributes
    | and relationships, create your own Shop model and set it here.
    |
    */

    'shop-model' => Litvinjuan\LaravelShopify\Models\Shop::class,

    /*
    |--------------------------------------------------------------------------
    | Shop Loader Class
    |--------------------------------------------------------------------------
    |
    | This is the class in charge of determining which shop to use for the
    | current request. You should create a class that implements the
    | ShopLoader interface and set it here.
    |
    | Read the documentation for further help settings this up.
    |
    */

    'shop-loader-class' => App\Utils\ShopLoader::class,
];
