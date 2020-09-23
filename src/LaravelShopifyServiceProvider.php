<?php

namespace Litvinjuan\LaravelShopify;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class LaravelShopifyServiceProvider extends ServiceProvider
{
    public function register()
    {
        config([
            'auth.guards.shopify' => array_merge([
                'driver' => 'shopify',
                'provider' => null,
            ], config('auth.guards.shopify', [])),
        ]);

        if (! $this->app->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/../config/laravel-shopify.php', 'laravel-shopify');
        }
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/laravel-shopify.php' => config_path('laravel-shopify.php'),
            ], ['laravel-shopify-config', 'config']);

            $migrationFileName = 'create_shops_table.php';
            if (! $this->migrationFileExists($migrationFileName)) {
                $this->publishes([
                    __DIR__ . "/../database/migrations/{$migrationFileName}.stub" => database_path('migrations/' . date('Y_m_d_His', time()) . '_' . $migrationFileName),
                ], ['laravel-shopify-migrations', 'migrations']);
            }
        }

        $this->configureGuard();
    }

    public function configureGuard()
    {
        Auth::resolved(function ($auth) {
            $auth->extend('shopify', function () use ($auth) {
                return tap($this->createGuard(), function ($guard) {
                    $this->app->refresh('request', $guard, 'setRequest');
                });
            });
        });
    }

    private function createGuard()
    {
        return new ShopifyGuard($this->app['request']);
    }

    public static function migrationFileExists(string $migrationFileName): bool
    {
        $len = strlen($migrationFileName);
        foreach (glob(database_path("migrations/*.php")) as $filename) {
            if ((substr($filename, -$len) === $migrationFileName)) {
                return true;
            }
        }

        return false;
    }
}
