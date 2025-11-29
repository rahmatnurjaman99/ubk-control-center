<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SystemRole: string implements HasLabel
{
    case SuperAdmin = 'super_admin';
    case Admin = 'admin';
    case Teacher = 'teacher';
    case Guardian = 'guardian';
    case Student = 'student';
    case PanelUser = 'panel_user';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::SuperAdmin => __('filament.roles.super_admin'),
            self::Admin => __('filament.roles.admin'),
            self::Teacher => __('filament.roles.teacher'),
            self::Guardian => __('filament.roles.guardian'),
            self::Student => __('filament.roles.student'),
            self::PanelUser => __('filament.roles.panel_user'),
        };
    }

    /**
     * @return list<string>
     */
    public static function panelAccessRoleValues(): array
    {
        return [
            self::SuperAdmin->value,
            self::Admin->value,
            self::Teacher->value,
            self::PanelUser->value,
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $role): array => [$role->value => $role->getLabel() ?? $role->value])
            ->all();
    }
}
