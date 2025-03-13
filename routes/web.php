<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OAuth\SpotifyAuthController;
use Laravel\WorkOS\Http\Middleware\ValidateSessionWithWorkOS;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth',
    ValidateSessionWithWorkOS::class,
])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Spotify OAuth
    Route::controller(SpotifyAuthController::class)
    ->as('oauth.spotify')
    ->group(function () {
        Route::get('oauth/spotify/redirect', 'redirect')->name('redirect');
        Route::get('oauth/spotify/callback', 'callback')->name('callback');
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
