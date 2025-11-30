<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'reference',
        'label',
        'type',
        'category',
        'payment_status',
        'payment_method',
        'amount',
        'currency',
        'due_date',
        'paid_at',
        'academic_year_id',
        'source_type',
        'source_id',
        'counterparty_name',
        'recorded_by',
        'notes',
        'metadata',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => TransactionType::class,
            'payment_status' => PaymentStatus::class,
            'amount' => 'decimal:2',
            'due_date' => 'date',
            'paid_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $transaction): void {
            if (blank($transaction->reference)) {
                $transaction->reference = self::generateReference();
            }
        });
    }

    public static function generateReference(): string
    {
        do {
            $reference = 'TRX-' . now()->format('Ymd') . '-' . Str::upper(Str::random(4));
        } while (self::withTrashed()->where('reference', $reference)->exists());

        return $reference;
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function source(): MorphTo
    {
        return $this->morphTo();
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
