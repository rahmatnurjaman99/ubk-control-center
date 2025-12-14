<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;

class ReceiptController
{
    public function transaction(Transaction $transaction): View
    {
        return view('receipts.transaction', [
            'transaction' => $transaction->load(['academicYear', 'recorder', 'fees.scholarship']),
        ]);
    }

    public function fee(Fee $fee): View
    {
        return view('receipts.fee', [
            'fee' => $fee->load(['student.guardian', 'academicYear', 'scholarship']),
        ]);
    }
}
