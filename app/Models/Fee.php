<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FeeStatus;
use App\Enums\FeeType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
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
            'due_date' => 'date',
            'paid_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $fee): void {
            if (blank($fee->reference)) {
                $fee->reference = self::generateReference();
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
}
