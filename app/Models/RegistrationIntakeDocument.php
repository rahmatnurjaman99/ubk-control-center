<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationIntakeDocument extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'registration_intake_id',
        'name',
        'type',
        'file_path',
        'notes',
    ];

    public function intake(): BelongsTo
    {
        return $this->belongsTo(RegistrationIntake::class, 'registration_intake_id');
    }
}
