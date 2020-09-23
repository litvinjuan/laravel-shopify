<?php

namespace litvinjuan\LaravelShopify\Commands;

use Illuminate\Console\Command;

class LaravelShopifyCommand extends Command
{
    public $signature = 'laravel-shopify';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
