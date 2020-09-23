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
php artisan vendor:publish --provider="Litvinjuan\LaravelShopify\LaravelShopifyServiceProvider" --tag="migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Litvinjuan\LaravelShopify\LaravelShopifyServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

``` php
// Not Implemented
```

## Testing

``` bash
composer test
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
