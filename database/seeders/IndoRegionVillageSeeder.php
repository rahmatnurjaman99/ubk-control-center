<?php

declare(strict_types=1);

namespace Database\Seeders;

use AzisHapidin\IndoRegion\RawDataGetter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class IndoRegionVillageSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            collect(RawDataGetter::getVillages())
                ->chunk(1000)
                ->each(function (Collection $chunk): void {
                    DB::table('villages')->insert($chunk->toArray());
                });
        });
    }
}
