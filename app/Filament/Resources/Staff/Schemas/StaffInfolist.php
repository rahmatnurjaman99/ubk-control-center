<?php

declare(strict_types=1);

namespace App\Filament\Resources\Staff\Schemas;

use App\Enums\StaffRole;
use App\Models\Staff;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StaffInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getProfileSection(),
                self::getEmergencySection(),
                self::getEducationSection(),
                self::getDocumentsSection(),
                self::getMetaSection(),
            ]);
    }

    private static function getProfileSection(): Section
    {
        return Section::make(__('filament.staff.sections.profile'))
            ->schema([
                ImageEntry::make('photo_url')
                    ->label(__('filament.staff.fields.photo'))
                    ->circular()
                    ->columnSpan(1),
                TextEntry::make('user.name')
                    ->label(__('filament.staff.fields.user'))
                    ->weight('medium'),
                TextEntry::make('staff_name')
                    ->label(__('filament.staff.fields.staff_name'))
                    ->weight('medium'),
                TextEntry::make('staff_number')
                    ->label(__('filament.staff.fields.staff_number')),
                TextEntry::make('gender')
                    ->label(__('filament.staff.fields.gender'))
                    ->badge()
                    ->formatStateUsing(fn (?string $state): ?string => match ($state) {
                        'male' => __('filament.staff.genders.male'),
                        'female' => __('filament.staff.genders.female'),
                        default => null,
                    }),
                TextEntry::make('user.roles.name')
                    ->label(__('filament.staff.fields.role'))
                    ->badge()
                    ->listWithLineBreaks()
                    ->state(fn (Staff $record): array => self::formatRoleState($record)),
                TextEntry::make('joined_on')
                    ->label(__('filament.staff.fields.joined_on'))
                    ->date(),
                TextEntry::make('phone')
                    ->label(__('filament.staff.fields.phone'))
                    ->icon('heroicon-o-phone')
                    ->copyable(),
                TextEntry::make('address')
                    ->label(__('filament.staff.fields.address'))
                    ->columnSpanFull(),
                TextEntry::make('province.name')
                    ->label(__('filament.staff.fields.province'))
                    ->placeholder('—'),
                TextEntry::make('regency.name')
                    ->label(__('filament.staff.fields.regency'))
                    ->placeholder('—'),
                TextEntry::make('district.name')
                    ->label(__('filament.staff.fields.district'))
                    ->placeholder('—'),
                TextEntry::make('village.name')
                    ->label(__('filament.staff.fields.village'))
                    ->placeholder('—'),
            ])
            ->columns(2);
    }

    private static function getEmergencySection(): Section
    {
        return Section::make(__('filament.staff.sections.emergency'))
            ->schema([
                TextEntry::make('emergency_contact_name')
                    ->label(__('filament.staff.fields.emergency_contact_name')),
                TextEntry::make('emergency_contact_phone')
                    ->label(__('filament.staff.fields.emergency_contact_phone'))
                    ->icon('heroicon-o-phone')
                    ->copyable(),
            ])
            ->columns(2);
    }

    private static function getEducationSection(): Section
    {
        return Section::make(__('filament.staff.sections.education'))
            ->schema([
                TextEntry::make('education_level')
                    ->label(__('filament.staff.fields.education_level')),
                TextEntry::make('education_institution')
                    ->label(__('filament.staff.fields.education_institution')),
                TextEntry::make('graduated_year')
                    ->label(__('filament.staff.fields.graduated_year')),
            ])
            ->columns(3);
    }

    private static function getDocumentsSection(): Section
    {
        return Section::make(__('filament.staff.sections.documents'))
            ->schema([
                RepeatableEntry::make('documents')
                    ->label(__('filament.staff.fields.documents'))
                    ->schema([
                        TextEntry::make('name')
                            ->label(__('filament.staff.fields.document_name'))
                            ->weight('medium'),
                        TextEntry::make('type')
                            ->label(__('filament.staff.fields.document_type'))
                            ->badge()
                            ->placeholder('—'),
                        TextEntry::make('file_path')
                            ->label(__('filament.staff.fields.document_file'))
                            ->icon('heroicon-o-arrow-up-on-square-stack')
                            ->url(fn (?string $state): ?string => filled($state) ? Storage::disk('public')->url($state) : null)
                            ->openUrlInNewTab()
                            ->copyable()
                            ->columnSpanFull(),
                        TextEntry::make('notes')
                            ->label(__('filament.staff.fields.document_notes'))
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    private static function getMetaSection(): Section
    {
        return Section::make(__('filament.staff.sections.metadata'))
            ->schema([
                TextEntry::make('created_at')
                    ->label(__('filament.staff.table.created_at'))
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label(__('filament.staff.table.updated_at'))
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->label(__('filament.staff.table.deleted_at'))
                    ->since()
                    ->visible(fn ($record): bool => filled($record?->deleted_at)),
            ])
            ->columns(3);
    }

    /**
     * @return list<string>
     */
    private static function formatRoleState(Staff $record): array
    {
        $names = $record->user?->roles
            ?->pluck('name')
            ->filter()
            ->unique()
            ->values()
            ->map(fn (string $name): string => self::formatRoleLabel($name) ?? $name)
            ->all();

        if (! empty($names)) {
            return $names;
        }

        $fallback = (string) $record->role;

        if ($fallback === '') {
            return [];
        }

        return [self::formatRoleLabel($fallback) ?? $fallback];
    }

    private static function formatRoleLabel(string $value): ?string
    {
        $enum = StaffRole::tryFrom($value);

        if ($enum !== null) {
            return $enum->getLabel();
        }

        return Str::headline(str_replace('_', ' ', $value));
    }
}
