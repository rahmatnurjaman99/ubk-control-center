<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FeeStatus;
use App\Enums\FeeType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

class Fee extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'student_id',
        'academic_year_id',
        'transaction_id',
        'reference',
        'title',
        'type',
        'amount',
        'currency',
        'due_date',
        'status',
        'paid_at',
        'description',
        'paid_amount',
        'allow_partial_payment',
        'requires_partial_approval',
        'partial_payment_approved_at',
        'partial_payment_approved_by',
        'scholarship_id',
        'scholarship_discount_amount',
        'scholarship_discount_percent',
        'metadata',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => FeeType::class,
            'status' => FeeStatus::class,
            'amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'due_date' => 'date',
            'paid_at' => 'datetime',
            'allow_partial_payment' => 'boolean',
            'requires_partial_approval' => 'boolean',
            'partial_payment_approved_at' => 'datetime',
            'scholarship_discount_amount' => 'decimal:2',
            'metadata' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $fee): void {
            if (blank($fee->reference)) {
                $fee->reference = self::generateReference();
            }

            if ($fee->paid_amount === null) {
                $fee->paid_amount = 0;
            }
        });

        static::saving(function (self $fee): void {
            $fee->paid_amount = max(0, min((float) $fee->amount, (float) ($fee->paid_amount ?? 0)));

            if ($fee->status === FeeStatus::Cancelled) {
                return;
            }

            if ($fee->paid_amount >= (float) $fee->amount) {
                $fee->status = FeeStatus::Paid;

                if ($fee->paid_at === null) {
                    $fee->paid_at = now();
                }

                return;
            }

            if ($fee->paid_amount > 0) {
                $fee->status = FeeStatus::Partial;

                return;
            }

            if ($fee->status === FeeStatus::Partial) {
                $fee->status = FeeStatus::Pending;
            }
        });
    }

    public static function generateReference(): string
    {
        do {
            $reference = 'FEE-' . now()->format('Ymd') . '-' . Str::upper(Str::random(4));
        } while (self::withTrashed()->where('reference', $reference)->exists());

        return $reference;
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function scholarship(): BelongsTo
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function partialPaymentApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partial_payment_approved_by');
    }

    public function getOutstandingAmountAttribute(): float
    {
        $amount = (float) $this->amount;
        $paid = (float) ($this->paid_amount ?? 0);

        return max($amount - $paid, 0);
    }

    public function canProcessPartialPayment(): bool
    {
        if (! $this->allow_partial_payment) {
            return false;
        }

        if (! $this->requires_partial_approval) {
            return true;
        }

        return $this->partial_payment_approved_at !== null;
    }

    public function hasScholarship(): bool
    {
        return $this->scholarship_id !== null;
    }

    public function formattedScholarshipDiscount(): ?string
    {
        if (! $this->hasScholarship()) {
            return null;
        }

        $parts = [];

        if ($this->scholarship?->name) {
            $parts[] = $this->scholarship->name;
        }

        if ($this->scholarship_discount_percent !== null) {
            $parts[] = $this->scholarship_discount_percent . '%';
        }

        if ((float) ($this->scholarship_discount_amount ?? 0) > 0) {
            $parts[] = Number::currency(
                (float) $this->scholarship_discount_amount,
                $this->currency ?? 'IDR',
            );
        }

        return $parts === [] ? null : implode(' • ', $parts);
    }

    public function scholarshipDiscountResult(float $amount = 0): float
    {
        if (! $this->hasScholarship()) {
            return 0;
        }

        if($this->scholarship_discount_percent !== null){
            return (int) ($amount * ($this->scholarship_discount_percent / 100));
        }

        return (int) $this->scholarship_discount_amount;
    }
}
