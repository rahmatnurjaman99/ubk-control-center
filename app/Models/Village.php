<?php

declare(strict_types=1);

namespace App\Models;

use AzisHapidin\IndoRegion\Traits\VillageTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Village extends Model
{
    use VillageTrait;

    protected $table = 'villages';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $hidden = [
        'district_id',
    ];

    public $timestamps = false;

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }
}
