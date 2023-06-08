<?php

namespace App\Providers;

use App\Concrete\Client;
use App\Contracts\ClientContract;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ClientContract::class, function () {
            return new Client([
                'headers' => [
                    'Connection' => 'close',
                    'X-Source-Platform' => 'humidityMapService',
                ],
                CURLOPT_FORBID_REUSE => true,
                CURLOPT_FRESH_CONNECT => true,
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
