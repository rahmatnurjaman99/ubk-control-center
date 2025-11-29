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

    public function run(): void
    {
        $definitions = [
            SystemRole::Admin->value => [
                'permissions' => self::FULL_ACCESS,
            ],
            SystemRole::Teacher->value => [
                'permissions' => $this->buildPermissions([
                    'Student' => ['ViewAny', 'View', 'Update'],
                    'Classroom' => ['ViewAny', 'View', 'Update'],
                    'Subject' => ['ViewAny', 'View'],
                    'SubjectCategory' => ['ViewAny', 'View'],
                    'AcademicYear' => ['ViewAny', 'View'],
                ]),
            ],
            SystemRole::Guardian->value => [
                'permissions' => $this->buildPermissions([
                    'Student' => ['ViewAny', 'View'],
                    'Guardian' => ['ViewAny', 'View'],
                    'Classroom' => ['ViewAny', 'View'],
                    'Subject' => ['ViewAny', 'View'],
                ]),
            ],
            SystemRole::Student->value => [
                'permissions' => $this->buildPermissions([
                    'Student' => ['ViewAny', 'View'],
                    'Classroom' => ['ViewAny', 'View'],
                    'Subject' => ['ViewAny', 'View'],
                    'AcademicYear' => ['ViewAny', 'View'],
                ]),
            ],
        ];

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
