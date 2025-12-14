<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeePartialApproval extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'fee_id',
        'guardian_id',
        'approved_by',
        'status',
        'scholarship_id',
        'reason',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function fee(): BelongsTo
    {
        return $this->belongsTo(Fee::class);
    }

    public function guardian(): BelongsTo
    {
        return $this->belongsTo(Guardian::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scholarship(): BelongsTo
    {
        return $this->belongsTo(Scholarship::class);
    }
}
