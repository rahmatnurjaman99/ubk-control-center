<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Fee;
use App\Models\Transaction;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class FinanceServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Transaction::creating(function (Transaction $transaction): void {
            if (blank($transaction->reference)) {
                $transaction->reference = Transaction::generateReference();
            }

            if (blank($transaction->recorded_by)) {
                $transaction->recorded_by = auth()->id();
            }
        });

        Fee::creating(function (Fee $fee): void {
            if (blank($fee->reference)) {
                $fee->reference = Fee::generateReference();
            }
        });
    }
}
