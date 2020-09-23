<?php

namespace litvinjuan\LaravelShopify;

use Illuminate\Support\ServiceProvider;
use litvinjuan\LaravelShopify\Commands\LaravelShopifyCommand;

class LaravelShopifyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/laravel-shopify.php' => config_path('laravel-shopify.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../resources/views' => base_path('resources/views/vendor/laravel-shopify'),
            ], 'views');

            $migrationFileName = 'create_laravel_shopify_table.php';
            if (! $this->migrationFileExists($migrationFileName)) {
                $this->publishes([
                    __DIR__ . "/../database/migrations/{$migrationFileName}.stub" => database_path('migrations/' . date('Y_m_d_His', time()) . '_' . $migrationFileName),
                ], 'migrations');
            }

            $this->commands([
                LaravelShopifyCommand::class,
            ]);
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-shopify');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-shopify.php', 'laravel-shopify');
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
