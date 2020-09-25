# Connect your Laravel application to the Shopify API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/litvinjuan/laravel-shopify.svg?style=flat-square)](https://packagist.org/packages/litvinjuan/laravel-shopify)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/litvinjuan/laravel-shopify/run-tests?label=tests)](https://github.com/litvinjuan/laravel-shopify/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/litvinjuan/laravel-shopify.svg?style=flat-square)](https://packagist.org/packages/litvinjuan/laravel-shopify)


This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require litvinjuan/laravel-shopify
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-shopify-migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --tag="laravel-shopify-config"
```

## Setup

The package comes with a default Shop model that you can use. However, if you want to add extra properties or relationships, you'll need to create your own Shop model that implements the `ShopContract` interface and uses the `ShopTrait` trait.
``` php
class Shop extends Model implements ShopContract
{
    use ShopTrait;

    // Your relationships, properties, etc.
}
```
**Note: Remember to also modify the migration before running it to add any additional columns you may need**

Then, you have to add your shop model to the package configuration. Publish the configuration if you haven't (using the command in the `Installation` section) and change the `shop-model` value to your own model:
``` php
// laravel-shopify.php
// ...

'shop-model' => App\Models\Shop::class // or your namespace 

// ...
```

The next step to set up our Shop owner, which is the model that's related to your shop. Right now, we only support the user as the owner, so go into your User model and add the following interface:
``` php
class User extends Authenticatable implements ShopifyOwner
{
    // ...
}
```

Now, there's two ways in which you can relate your users and shops.  
If you want each user to only have one shop, then you should the `HasShop` trait. If you want each user to own multiple shops, meaning that one user has access to many shops, use the `HasShops` trait.
``` php
class User extends Authenticatable implements ShopifyOwner
{
    use HasShop; // one shop per user
    use HasShops; // multiple shops per user
}
```
**Note: only include one trait in your model**

Once your user model is set up, go into the configuration file once again and change the `user-model` value to your own model
``` php
// laravel-shopify.php
// ...

'user-model' => App\Models\User::class // or your namespace 

// ...
```

The last step is to implement the `ShopLoader`. This is a class that will tell shopify how to determine which shop to use during requests. Below is an example of a ShopLoader that works for a user that only has one shop, and simply tells shopify to use the shop of the current authenticated user:
``` php
class UserShopLoader implements ShopLoader
{
    public function load(): ?ShopContract
    {
        if (! Auth::check()) {
            return null;
        }

        return Auth::user()->shop;
    }
}
```

Once you've implemented your own ShopLoader, or used the own provided above, you'll need to add it to the shopify configuration file:
``` php
// laravel-shopify.php
// ...

'shop-loader-class' => App\Loaders\MyCustomShopLoader::class // or your namespace 

// ...
```

Lastly, you'll need to create an App in your Shopify Developer account, and set up the following variables in your `.env` file:

`SHOPIFY_API_KEY=` This is your app's api key. You can find it in your app dashboard  
`SHOPIFY_API_SECRET=` This is your app's api secret. You can find it in your app dashboard  
`SHOPIFY_API_SCOPES=` If no scopes are provided during an authentication request, shopify will use these.  
`SHOPIFY_API_CALLBACK_URL=` Callback url to use after the user has installed the app on shopify. Remember to whitelist this URL in your app dashboard.  

## Usage

This package adds a few things to your laravel application.  
  
First, you have a new middleware `signed.shopify`, which verifies that the request is coming from shopify and has a valid signature. Include this middleware in any webhook routes or other requests where you want to verify that the request came from Shopify. 
``` php
Route::post('/shopify/webhook', 'MyController@webhook')->middleware(['signed.shopify']);
```

There's also a new Shopify authentication guard, which allows you to authenticate a user when they are viewing your app within the Shopify Admin Panel. You should use this auth guard for any routes that the user may access from within the Shopify Admin Panel, and not through your own website.
``` php
Route::get('/shopify/app', 'ShopifyController@app')->middleware(['auth:shopify']);
```

### Authentication

To authenticate your users in Shopify, you'll need to add two routes: one to redirect the user to Shopify's Oauth screen, and one for the callback from that screen:
``` php
Route::get('/redirect', 'ShopifyController@redirect');
Route::get('/callback', 'ShopifyController@callback');
```

In your redirect route, you'll need to receive a shop domain parameter for the Shopify store your user wants to connect to. Then, simply redirect them with the following:
``` php
public function redirect(Request $request)
{
    $shopDomain = $request->input('shop');
    return Shopify::redirect($user, $shopDomain);
}
```

After the user accepts the scopes and installs the app on their Shopify store, they'll be redirected back to your callback url. The last step, is to configure the callback to generate the necessary access token for the shop:
``` php
public function callback(Request $request)
{
    $shopDomain = $request->input('shop');
    return Shopify::redirect($user, $shopDomain);
}
```

You can also pass a different callback url or scopes as the third and fourth arguments to the redirect method
``` php
public function callback(Request $request)
{
    $shopDomain = $request->input('shop');
    return Shopify::redirect($user, $shopDomain, 'https://mysite.com/custom-callback', ['scope1', 'another-cool-scope']);
}
```

### Shopify Facade

The package makes a Shopify Facade available that has a few useful methods.
``` php
Shopify::redirect               // First step of the Shopify authentication
Shopify::callback               // Last step of the Shopify authentication
Shopify::getShop                // Get the current shop. For routes viewed from the Shopigy Admin panel, this will be the current Shop. For routes within your own domain, this will be the one inferred from the ShopLoader you set in your config file
Shopify::setShop                // Manually set the current shop
Shopify::forget                 // Forget the current shop
Shopify::hasShop                // Returns true if there is a current shop set
Shopify::isValidHmac            // Validates the Shopify hmac signature for the current request
Shopify::assertShopExists       // Throws an exception if the Shopify store domain isn't found in the database
```


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Juan Litvin](https://github.com/litvinjuan)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
