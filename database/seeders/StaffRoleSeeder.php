<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\StaffRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class StaffRoleSeeder extends Seeder
{
    /**
     * Sync Spatie roles with the StaffRole enum.
     */
    public function run(): void
    {
        $guard = config('filament.auth.guard') ?? config('auth.defaults.guard');

        foreach (StaffRole::cases() as $staffRole) {
            Role::query()->firstOrCreate(
                ['name' => $staffRole->value, 'guard_name' => $guard],
                [],
            );
        }
    }
}
