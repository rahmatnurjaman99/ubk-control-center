<?php

declare(strict_types=1);

namespace App\Models;

use AzisHapidin\IndoRegion\Traits\DistrictTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    use DistrictTrait;

    protected $table = 'districts';

    protected $hidden = [
        'regency_id',
    ];

    public $timestamps = false;

    public function regency(): BelongsTo
    {
        return $this->belongsTo(Regency::class);
    }

    public function villages(): HasMany
    {
        return $this->hasMany(Village::class);
    }
}
