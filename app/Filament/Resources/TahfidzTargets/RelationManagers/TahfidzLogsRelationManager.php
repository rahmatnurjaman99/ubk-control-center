<?php

declare(strict_types=1);

namespace App\Filament\Resources\TahfidzTargets\RelationManagers;

use App\Enums\TahfidzLogStatus;
use App\Support\Quran\QuranOptions;
use Closure;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TahfidzLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'logs';
    protected static ?string $title = null;
    private ?array $cachedIncompleteSegmentSurahs = null;

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('filament.tahfidz_logs.navigation.label');
    }

    private function getSurahLabelForForm(): ?string
    {
        if (! $this->hasRemainingSegments()) {
            return __('filament.tahfidz_logs.messages.all_segments_completed');
        }

        $segmentSurah = $this->getOwnerRecord()?->primarySegment()?->surah_id;

        return $segmentSurah ? QuranOptions::surahLabel($segmentSurah) : null;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(fn (): string => __('filament.tahfidz_logs.sections.review'))
                    ->description(fn (): ?string => $this->getSurahLabelForForm())
                    ->schema([
                        DatePicker::make('recorded_on')
                            ->label(__('filament.tahfidz_logs.fields.recorded_on'))
                            ->native(false)
                            ->required(),
                        Select::make('status')
                            ->label(__('filament.tahfidz_logs.fields.status'))
                            ->options(TahfidzLogStatus::options())
                            ->native(false)
                            ->required(),
                        Toggle::make('is_revision')
                            ->label(__('filament.tahfidz_logs.fields.is_revision'))
                            ->inline(false)
                            ->default(false)
                            ->columnSpan(2),
                        Select::make('surah_id')
                            ->label(__('filament.tahfidz_logs.fields.surah'))
                            ->options(fn (Get $get): array => $this->getSurahOptionsIncludingCurrent(
                                filled($get('surah_id')) ? (int) $get('surah_id') : null,
                            ))
                            ->default(function (): ?int {
                                $options = $this->getSegmentSurahOptions();
                                $primary = $this->getOwnerRecord()->primarySegment()?->surah_id;

                                if ($primary !== null && array_key_exists($primary, $options)) {
                                    return $primary;
                                }

                                return $options !== [] ? (int) array_key_first($options) : null;
                            })
                            ->native(false)
                            ->searchable()
                            ->live()
                            ->partiallyRenderComponentsAfterStateUpdated(['start_ayah_number', 'end_ayah_number'])
                            // ->dehydrated(fn (?int $state): bool => filled($state) || $this->hasRemainingSegments())
                            ->disabledOn('edit')
                            ->visible(fn (?int $state): bool => $this->hasRemainingSegments() || filled($state))
                            ->required(fn (?int $state): bool => $this->hasRemainingSegments() || filled($state)),
                        Select::make('start_ayah_number')
                            ->label(__('filament.tahfidz_logs.fields.start_ayah'))
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
                            ->required()
                            ->visible(fn (): bool => $this->hasRemainingSegments()),
                        Select::make('end_ayah_number')
                            ->label(__('filament.tahfidz_logs.fields.end_ayah'))
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
                            ->partiallyRenderAfterStateUpdated()
                            ->required()
                            ->visible(fn (): bool => $this->hasRemainingSegments())
                            ->rule(function (Get $get) {
                                return function (string $attribute, $value, Closure $fail) use ($get): void {
                                    $start = (int) ($get('start_ayah_number') ?? 0);
                                    $end = (int) ($value ?? 0);

                                    if ($start > 0 && $end < $start) {
                                        $fail(__('filament.tahfidz_targets.validation.end_before_start'));
                                    }
                                };
                            }),
                        TextInput::make('memorization_score')
                            ->label(__('filament.tahfidz_logs.fields.memorization_score'))
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->nullable(),
                        TextInput::make('tajwid_score')
                            ->label(__('filament.tahfidz_logs.fields.tajwid_score'))
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->nullable(),
                        TextInput::make('fluency_score')
                            ->label(__('filament.tahfidz_logs.fields.fluency_score'))
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->nullable(),
                        Textarea::make('notes')
                            ->label(__('filament.tahfidz_logs.fields.notes'))
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('recorded_on')
                    ->label(__('filament.tahfidz_logs.table.recorded_on'))
                    ->date()
                    ->sortable(),
                TextColumn::make('surah_id')
                    ->label(__('filament.tahfidz_logs.table.surah'))
                    ->formatStateUsing(fn (?int $state): ?string => QuranOptions::surahLabel($state)),
                TextColumn::make('start_ayah_number')
                    ->label(__('filament.tahfidz_logs.table.ayah_range'))
                    ->formatStateUsing(fn ($state, $record): string => sprintf(
                        'Ayah %d–%d',
                        $record->start_ayah_number,
                        $record->end_ayah_number ?? $record->start_ayah_number,
                    ))
                    ->badge(),
                TextColumn::make('status')
                    ->label(__('filament.tahfidz_logs.table.status'))
                    ->badge()
                    ->formatStateUsing(fn (?TahfidzLogStatus $state): ?string => $state?->label()),
                TextColumn::make('memorization_score')
                    ->label(__('filament.tahfidz_logs.table.memorization_score'))
                    ->suffix('%')
                    ->alignCenter(),
                TextColumn::make('tajwid_score')
                    ->label(__('filament.tahfidz_logs.table.tajwid_score'))
                    ->suffix('%')
                    ->alignCenter(),
                TextColumn::make('fluency_score')
                    ->label(__('filament.tahfidz_logs.table.fluency_score'))
                    ->suffix('%')
                    ->alignCenter(),
                IconColumn::make('is_revision')
                    ->label(__('filament.tahfidz_logs.table.is_revision'))
                    ->boolean(),
            ])
            ->defaultSort('recorded_on', 'desc')
            ->headerActions([
                CreateAction::make()
                    ->label(__('filament.tahfidz_logs.actions.create')),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    /**
     * @return array<int, string>
     */
    private function getSegmentSurahOptions(): array
    {
        if ($this->cachedIncompleteSegmentSurahs !== null) {
            return $this->cachedIncompleteSegmentSurahs;
        }

        $target = $this->getOwnerRecord();
        $segments = $target?->segments;

        if ($segments?->isNotEmpty()) {
            $completionMap = $target->logs()
                ->selectRaw('surah_id, MAX(COALESCE(end_ayah_number, start_ayah_number, 0)) as max_ayah')
                ->groupBy('surah_id')
                ->pluck('max_ayah', 'surah_id')
                ->map(fn ($value): int => (int) $value);

            return $this->cachedIncompleteSegmentSurahs = $segments
                ->filter(function ($segment) use ($completionMap): bool {
                    $endAyah = (int) ($segment->end_ayah_number ?? $segment->start_ayah_number ?? 0);

                    if ($endAyah === 0) {
                        return true;
                    }

                    $completedAyah = $completionMap[$segment->surah_id] ?? 0;

                    return $completedAyah < $endAyah;
                })
                ->mapWithKeys(fn ($segment): array => [
                    $segment->surah_id => QuranOptions::surahLabel($segment->surah_id) ?? (string) $segment->surah_id,
                ])
                ->toArray();
        }

        return $this->cachedIncompleteSegmentSurahs = [];
    }

    /**
     * @return array<int, string>
     */
    private function getSurahOptionsIncludingCurrent(?int $currentSurah): array
    {
        $options = $this->getSegmentSurahOptions();

        if ($currentSurah !== null && ! array_key_exists($currentSurah, $options)) {
            $options[$currentSurah] = QuranOptions::surahLabel($currentSurah) ?? (string) $currentSurah;
        }

        return $options;
    }

    private function hasRemainingSegments(): bool
    {
        return ! empty($this->getSegmentSurahOptions());
    }
}
