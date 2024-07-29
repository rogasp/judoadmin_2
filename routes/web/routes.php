<?php
use App\Http\Controllers\Central\RegisterTenantController;
use App\Livewire\Auth\RegisterForm;
use Illuminate\Support\Facades\Route;

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->as('pages:')->group(static function () {
        Route::view('/', 'pages.home')->name('home');
        Route::middleware(['auth'])->group(static function (): void {
            // Du kan lägga till skyddade routes här
        });
        Route::prefix('auth')->as('auth:')->group(static function (): void {
            Route::get('/register/{plan?}', [RegisterTenantController::class, 'showRegistrationForm'])->name('register');
            Route::post('/register', [RegisterTenantController::class, 'store'])->name('register.store');
        });
    });
}
