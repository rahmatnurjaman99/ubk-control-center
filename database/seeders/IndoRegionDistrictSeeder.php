<?php

declare(strict_types=1);

namespace Database\Seeders;

use AzisHapidin\IndoRegion\RawDataGetter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndoRegionDistrictSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('districts')->insert(RawDataGetter::getDistricts());
    }
}
