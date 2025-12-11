<?php

declare(strict_types=1);

namespace App\Models;

use AzisHapidin\IndoRegion\Traits\ProvinceTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    use ProvinceTrait;

    protected $table = 'provinces';

    public $timestamps = false;

    public function regencies(): HasMany
    {
        return $this->hasMany(Regency::class);
    }
}
