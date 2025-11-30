<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\GoogleOAuthController;
use App\Http\Controllers\ReceiptController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');

Route::middleware('guest')->prefix('auth/google')->name('auth.google.')->group(function (): void {
    Route::get('redirect', [GoogleOAuthController::class, 'redirect'])->name('redirect');
    Route::get('callback', [GoogleOAuthController::class, 'callback'])->name('callback');
});

Route::middleware('auth')->prefix('receipts')->name('receipts.')->group(function (): void {
    Route::get('transactions/{transaction}', [ReceiptController::class, 'transaction'])
        ->name('transactions.show');

    Route::get('fees/{fee}', [ReceiptController::class, 'fee'])
        ->name('fees.show');
});
