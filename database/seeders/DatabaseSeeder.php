<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\SystemRole;
use App\Enums\UserStatus;
use App\Models\User;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SystemRolesSeeder::class,
            StaffRoleSeeder::class,
        ]);

        $superAdminRole = Utils::createRole(SystemRole::SuperAdmin->value);
        $superAdminRole->syncPermissions(Utils::getPermissionModel()::pluck('id'));

        Utils::createPanelUserRole();

        $superAdmin = User::query()->firstOrCreate(
            ['email' => 'openkzpc@gmail.com'],
            [
                'name' => 'Super Admin',
                'status' => UserStatus::Active,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );

        if (! $superAdmin->hasRole($superAdminRole)) {
            $superAdmin->assignRole($superAdminRole);
        }
    }
}
