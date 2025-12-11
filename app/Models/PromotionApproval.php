<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PromotionApprovalStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromotionApproval extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'student_id',
        'current_academic_year_id',
        'target_academic_year_id',
        'target_classroom_id',
        'target_grade_level',
        'outstanding_amount',
        'status',
        'requested_by',
        'approved_by',
        'approved_at',
        'notes',
        'decision_notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => PromotionApprovalStatus::class,
            'outstanding_amount' => 'decimal:2',
            'approved_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function currentAcademicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'current_academic_year_id');
    }

    public function targetAcademicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'target_academic_year_id');
    }

    public function targetClassroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'target_classroom_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
