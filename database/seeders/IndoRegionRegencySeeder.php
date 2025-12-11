<?php

declare(strict_types=1);

namespace Database\Seeders;

use AzisHapidin\IndoRegion\RawDataGetter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndoRegionRegencySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('regencies')->insert(RawDataGetter::getRegencies());
    }
}
