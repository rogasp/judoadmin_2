<?php

use Illuminate\Support\Facades\Route;
foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->as('pages:')->group(static function () {
        Route::view('/', 'pages.home')->name('home');
        Route::middleware(['auth'])->group(static function (): void {

        });
        Route::prefix('auth')->as('auth:')->group(static function (): void {
            Route::view('register', 'pages.auth.register')->name('register');
        });
    });
}
