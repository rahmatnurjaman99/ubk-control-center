<?php

declare(strict_types=1);

namespace App\Filament\Resources\TahfidzTargets\Schemas;

use App\Enums\TahfidzTargetStatus;
use App\Models\Student;
use App\Models\TahfidzTargetSegment;
use App\Support\Quran\QuranOptions;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class TahfidzTargetForm
{
    public static function configure(Schema $schema, bool $forStudentRelation = false): Schema
    {
        return $schema
            ->components([
                self::getAssignmentSection($forStudentRelation),
                self::getQuranSection(),
                self::getTimelineSection(),
                self::getNotesSection(),
            ])
            ->columns(2);
    }

    private static function getAssignmentSection(bool $forStudentRelation): Section
    {
        return Section::make(__('filament.tahfidz_targets.sections.assignment'))
            ->schema([
                $forStudentRelation
                    ? self::getStudentHiddenComponent()
                    : self::getStudentComponent(),
                self::getClassroomComponent(),
                self::getAssignedByComponent(),
                self::getStatusComponent(),
                self::getRepetitionComponent(),
                self::getTagComponent(),
            ])
            ->columns(2);
    }

    private static function getQuranSection(): Section
    {
        return Section::make(__('filament.tahfidz_targets.sections.quran_scope'))
            ->schema([
                self::getSegmentsComponent(),
            ])
            ->columns(1);
    }

    private static function getTimelineSection(): Section
    {
        return Section::make(__('filament.tahfidz_targets.sections.timeline'))
            ->schema([
                self::getAssignedOnComponent(),
                self::getDueOnComponent(),
            ])
            ->columns(2);
    }

    private static function getNotesSection(): Section
    {
        return Section::make()
            ->schema([
                Textarea::make('notes')
                    ->label(__('filament.tahfidz_targets.fields.notes'))
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }

    private static function getStudentComponent(): Select
    {
        return Select::make('student_id')
            ->label(__('filament.tahfidz_targets.fields.student'))
            ->relationship('student', 'full_name')
            ->searchable()
            ->preload()
            ->required()
            ->native(false)
            ->live()
            ->partiallyRenderComponentsAfterStateUpdated(['classroom_id'])
            ->afterStateUpdated(function (Set $set, ?int $state): void {
                if ($state === null) {
                    $set('classroom_id', null);

                    return;
                }

                $classroomId = Student::query()
                    ->whereKey($state)
                    ->value('classroom_id');

                $set('classroom_id', $classroomId);
            });
    }

    private static function getStudentHiddenComponent(): Hidden
    {
        return Hidden::make('student_id')
            ->dehydrated()
            ->default(
                fn (?RelationManager $livewire) => $livewire?->getOwnerRecord()?->getKey(),
            );
    }

    private static function getClassroomComponent(): Select
    {
        return Select::make('classroom_id')
            ->label(__('filament.tahfidz_targets.fields.classroom'))
            ->relationship('classroom', 'name')
            ->searchable()
            ->preload()
            ->nullable()
            ->native(false);
    }

    private static function getAssignedByComponent(): Select
    {
        return Select::make('assigned_by_id')
            ->label(__('filament.tahfidz_targets.fields.assigned_by'))
            ->relationship('assignedBy', 'name')
            ->searchable()
            ->preload()
            ->default(fn (): ?int => Auth::id())
            ->nullable()
            ->native(false);
    }

    private static function getStatusComponent(): Select
    {
        return Select::make('status')
            ->label(__('filament.tahfidz_targets.fields.status'))
            ->options(TahfidzTargetStatus::options())
            ->required()
            ->native(false)
            ->default(TahfidzTargetStatus::Assigned);
    }

    private static function getRepetitionComponent(): TextInput
    {
        return TextInput::make('target_repetitions')
            ->label(__('filament.tahfidz_targets.fields.target_repetitions'))
            ->numeric()
            ->minValue(1)
            ->maxValue(20)
            ->default(3)
            ->required();
    }

    private static function getTagComponent(): TextInput
    {
        return TextInput::make('tag')
            ->label(__('filament.tahfidz_targets.fields.tag'))
            ->maxLength(50);
    }

    private static function getSegmentsComponent(): Repeater
    {
        return Repeater::make('segments')
            ->label(__('filament.tahfidz_targets.fields.segments'))
            ->relationship('segments')
            ->orderColumn('sequence')
            ->itemLabel(fn (?array $state, Schema $container): string => self::formatSegmentLabel(
                $state,
                $container->getRecord() instanceof Model ? $container->getRecord() : null,
            ))
            ->schema([
                Select::make('surah_id')
                    ->label(__('filament.tahfidz_targets.fields.surah'))
                    ->options(fn (): array => QuranOptions::surahOptions())
                    ->required()
                    ->native(false)
                    ->searchable()
                    ->live()
                    ->disabled(function (?Model $record): bool {
                        if ($record === null) {
                            return false;
                        }

                        if ($record instanceof TahfidzTargetSegment) {
                            return $record->target?->logs()->exists() ?? false;
                        }

                        return $record->logs()->exists();
                    })
                    ->dehydrated()
                    ->partiallyRenderComponentsAfterStateUpdated([
                        'start_ayah_number',
                        'end_ayah_number',
                        'start_preview',
                        'end_preview',
                    ]),
                Select::make('start_ayah_number')
                    ->label(__('filament.tahfidz_targets.fields.start_ayah'))
                    ->options(function (Get $get): array {
                        $surahId = $get('surah_id');

                        if (blank($surahId)) {
                            return [];
                        }

                        return QuranOptions::ayahOptions((int) $surahId);
                    })
                    ->disabled(fn (Get $get): bool => blank($get('surah_id')))
                    ->native(false)
                    ->searchable()
                    ->live()
                    ->partiallyRenderComponentsAfterStateUpdated([
                        'end_ayah_number',
                        'start_preview',
                        'end_preview',
                    ])
                    ->required()
                    ->afterStateUpdated(function (Set $set, ?int $state): void {
                        if ($state !== null) {
                            $set('end_ayah_number', $state);
                        }
                    }),
                Select::make('end_ayah_number')
                    ->label(__('filament.tahfidz_targets.fields.end_ayah'))
                    ->options(function (Get $get): array {
                        $surahId = $get('surah_id');

                        if (blank($surahId)) {
                            return [];
                        }

                        return QuranOptions::ayahOptions((int) $surahId);
                    })
                    ->disabled(fn (Get $get): bool => blank($get('surah_id')))
                    ->native(false)
                    ->searchable()
                    ->live()
                    ->partiallyRenderComponentsAfterStateUpdated(['end_preview'])
                    ->required()
                    ->rule(function (Get $get): Closure {
                        return function (string $attribute, $value, Closure $fail) use ($get): void {
                            $start = (int) ($get('start_ayah_number') ?? 0);
                            $end = (int) ($value ?? 0);

                            if ($start > 0 && $end < $start) {
                                $fail(__('filament.tahfidz_targets.validation.end_before_start'));
                            }
                        };
                    }),
                TextEntry::make('start_preview')
                    ->label(__('filament.tahfidz_targets.fields.start_ayah_preview'))
                    ->state(function (Get $get): string {
                        $surah = $get('surah_id');
                        $ayah = $get('start_ayah_number');

                        $text = QuranOptions::ayahArabic(
                            blank($surah) ? null : (int) $surah,
                            blank($ayah) ? null : (int) $ayah,
                        );

                        return $text ?? __('filament.tahfidz_targets.placeholders.no_ayah_selected');
                    })
                    ->visible(fn (Get $get): bool => filled($get('surah_id')) && filled($get('start_ayah_number')))
                    ->columnSpanFull(),
                TextEntry::make('end_preview')
                    ->label(__('filament.tahfidz_targets.fields.end_ayah_preview'))
                    ->state(function (Get $get): string {
                        $surah = $get('surah_id');
                        $ayah = $get('end_ayah_number');

                        $text = QuranOptions::ayahArabic(
                            blank($surah) ? null : (int) $surah,
                            blank($ayah) ? null : (int) $ayah,
                        );

                        return $text ?? __('filament.tahfidz_targets.placeholders.no_ayah_selected');
                    })
                    ->visible(fn (Get $get): bool => filled($get('surah_id')) && filled($get('end_ayah_number')))
                    ->columnSpanFull(),
            ])
            ->columns(2)
            ->columnSpanFull()
            ->minItems(1)
            ->defaultItems(1)
            ->addActionLabel(__('filament.tahfidz_targets.actions.add_segment'))
            ->collapsible()
            ->collapsed();
    }

    private static function formatSegmentLabel(?array $state, ?Model $record = null): string
    {
        $baseLabel = __('filament.tahfidz_targets.labels.segment');

        $surahId = $state['surah_id'] ?? ($record?->surah_id ?? null);
        if (blank($surahId)) {
            return $baseLabel;
        }

        $surahLabel = QuranOptions::surahLabel((int) $surahId) ?? $baseLabel;
        $start = $state['start_ayah_number'] ?? ($record?->start_ayah_number ?? null);
        $end = $state['end_ayah_number'] ?? ($record?->end_ayah_number ?? null);

        if ($start === null) {
            return "{$baseLabel}: {$surahLabel}";
        }

        $end ??= $start;

        return sprintf(
            '%s: %s (%d-%d)',
            $baseLabel,
            $surahLabel,
            (int) $start,
            (int) $end,
        );
    }

    private static function getAssignedOnComponent(): DatePicker
    {
        return DatePicker::make('assigned_on')
            ->label(__('filament.tahfidz_targets.fields.assigned_on'))
            ->native(false)
            ->default(now());
    }

    private static function getDueOnComponent(): DatePicker
    {
        return DatePicker::make('due_on')
            ->label(__('filament.tahfidz_targets.fields.due_on'))
            ->native(false)
            ->after('assigned_on');
    }

}
