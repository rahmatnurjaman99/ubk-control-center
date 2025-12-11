<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\SystemRole;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class SystemRolesSeeder extends Seeder
{
    private const FULL_ACCESS = '*';

    /**
     * @var list<string>
     */
    private const READ_PERMISSIONS = ['ViewAny', 'View'];

    /**
     * @var list<string>
     */
    private const MANAGE_PERMISSIONS = ['ViewAny', 'View', 'Create', 'Update', 'Delete'];

    /**
     * Role privilege map describing which permissions should be synced
     * for every non-admin system role.
     *
     * @var array<string, array<string, list<string>>>
     */
    private const ROLE_PRIVILEGES = [
        SystemRole::Teacher->value => [
            'AcademicYear' => self::READ_PERMISSIONS,
            'Classroom' => ['ViewAny', 'View', 'Update'],
            'Schedule' => self::READ_PERMISSIONS,
            'Student' => ['ViewAny', 'View', 'Update'],
            'StudentAttendance' => ['ViewAny', 'View', 'Create', 'Update', 'Delete'],
            'StaffAttendance' => self::READ_PERMISSIONS,
            'PromotionApproval' => ['ViewAny', 'View', 'Create'],
            'Fee' => self::READ_PERMISSIONS,
            'Transaction' => self::READ_PERMISSIONS,
        ],
        SystemRole::Guardian->value => [
            'AcademicYear' => self::READ_PERMISSIONS,
            'Classroom' => self::READ_PERMISSIONS,
            'Schedule' => self::READ_PERMISSIONS,
            'Student' => self::READ_PERMISSIONS,
            'StudentAttendance' => self::READ_PERMISSIONS,
            'PromotionApproval' => self::READ_PERMISSIONS,
            'Fee' => self::READ_PERMISSIONS,
            'Transaction' => self::READ_PERMISSIONS,
        ],
        SystemRole::PanelUser->value => [
            'AcademicYear' => self::READ_PERMISSIONS,
            'Classroom' => self::READ_PERMISSIONS,
            'Student' => ['ViewAny', 'View', 'Update'],
            'Guardian' => self::READ_PERMISSIONS,
            'Schedule' => self::MANAGE_PERMISSIONS,
            'FeeTemplate' => self::MANAGE_PERMISSIONS,
            'Fee' => self::MANAGE_PERMISSIONS,
            'Transaction' => self::MANAGE_PERMISSIONS,
            'RegistrationIntake' => self::MANAGE_PERMISSIONS,
            'PromotionApproval' => ['ViewAny', 'View', 'Update'],
            'StaffAttendance' => self::READ_PERMISSIONS,
            'StudentAttendance' => self::READ_PERMISSIONS,
        ],
        SystemRole::Student->value => [
            'AcademicYear' => self::READ_PERMISSIONS,
            'Classroom' => self::READ_PERMISSIONS,
            'Schedule' => self::READ_PERMISSIONS,
            'Student' => self::READ_PERMISSIONS,
            'StudentAttendance' => self::READ_PERMISSIONS,
            'Fee' => self::READ_PERMISSIONS,
            'Transaction' => self::READ_PERMISSIONS,
        ],
    ];

    public function run(): void
    {
        $definitions = [
            SystemRole::Admin->value => [
                'permissions' => self::FULL_ACCESS,
            ],
        ];

        foreach (self::ROLE_PRIVILEGES as $role => $privileges) {
            $definitions[$role]['permissions'] = $this->buildPermissions($privileges);
        }

        $guard = config('filament.auth.guard') ?? config('auth.defaults.guard');

        foreach ($definitions as $roleName => $definition) {
            /** @var \App\Models\Role $role */
            $role = Role::query()->firstOrCreate(
                ['name' => $roleName, 'guard_name' => $guard],
                [],
            );

            $permissions = $definition['permissions'];

            if ($permissions === self::FULL_ACCESS) {
                $role->syncPermissions(Permission::query()->pluck('id'));

                continue;
            }

            $permissionModels = Permission::query()
                ->whereIn('name', $permissions)
                ->pluck('id')
                ->all();

            $role->syncPermissions($permissionModels);
        }
    }

    /**
     * @param array<string, list<string>> $resourceAbilities
     * @return list<string>
     */
    private function buildPermissions(array $resourceAbilities): array
    {
        return collect($resourceAbilities)
            ->map(
                fn (array $abilities, string $resource): Collection => collect($abilities)
                    ->map(
                        fn (string $ability): string => sprintf('%s:%s', $ability, $resource),
                    ),
            )
            ->flatten()
            ->unique()
            ->values()
            ->all();
    }
}
