<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\GoogleOAuthController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');

Route::middleware('guest')->prefix('auth/google')->name('auth.google.')->group(function (): void {
    Route::get('redirect', [GoogleOAuthController::class, 'redirect'])->name('redirect');
    Route::get('callback', [GoogleOAuthController::class, 'callback'])->name('callback');
});
