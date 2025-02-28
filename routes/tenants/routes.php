<?php

// routes/tenant.php

use Stancl\Tenancy\Features\UserImpersonation;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->as('pages:tenants:')->group(static function (): void {
    Route::middleware(['guest'])->prefix('auth')->as('auth:')->group(static function (): void {
        Route::view('login', 'pages.auth.login')->name('login');
        Route::get('/impersonate/{token}', function ($token) {
            return UserImpersonation::makeResponse($token);
        })->name('impersonate');
    });
    Route::middleware(['auth'])->group(static function (): void {
        Route::view('/', 'pages.tenants.home')->name('home');
    });
});
