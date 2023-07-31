<?php

namespace App\Providers;

use App\Services\Util\AccredifyHttpClient;
use App\Services\Util\SignatureHash;
use App\Services\Verification\JsonFileVerificationProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(JsonFileVerificationProvider::class, function ($app){
            return new JsonFileVerificationProvider($app->make(AccredifyHttpClient::class), $app->make(SignatureHash::class));
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
