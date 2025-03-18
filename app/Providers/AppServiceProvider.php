<?php

namespace App\Providers;

use App\Interfaces\Spotify;
use Illuminate\Support\ServiceProvider;
use App\Services\Spotify\SpotifyService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            Spotify::class,
            SpotifyService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
