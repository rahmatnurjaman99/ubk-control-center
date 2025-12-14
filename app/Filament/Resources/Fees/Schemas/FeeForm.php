<?php

declare(strict_types=1);

namespace App\Filament\Resources\Fees\Schemas;

use App\Enums\FeeStatus;
use App\Enums\FeeType;
use App\Enums\ScholarshipType;
use App\Models\Classroom;
use App\Models\Fee;
use App\Models\Scholarship;
use App\Models\Student;
use App\Support\AcademicYearResolver;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Number;

class FeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::getPrimarySection(),
                self::getBillingSection(),
                self::getScheduleSection(),
                self::getMetaSection(),
            ])
            ->columns(1);
    }

    private static function getPrimarySection(): Section
    {
        return Section::make(__('filament.fees.fields.title'))
            ->columns(2)
            ->schema([
                Select::make('academic_year_id')
                    ->label(__('filament.fees.fields.academic_year'))
                    ->relationship('academicYear', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->default(fn (): ?int => AcademicYearResolver::currentId())
                    ->nullable(),
                TextInput::make('reference')
                    ->label(__('filament.fees.fields.reference'))
                    ->default(fn (): string => Fee::generateReference())
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(50)
                    ->readOnly(),
                TextInput::make('title')
                    ->label(__('filament.fees.fields.title'))
                    ->required()
                    ->maxLength(255),
                Select::make('type')
                    ->label(__('filament.fees.fields.type'))
                    ->options(FeeType::options())
                    ->enum(FeeType::class)
                    ->native(false)
                    ->required(),
                Select::make('classroom_filter')
                    ->label(__('filament.fees.fields.classroom'))
                    ->options(fn (): array => Classroom::query()
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all())
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->default(fn (?Fee $record): ?int => $record?->student?->classroom_id)
                    ->live()
                    ->afterStateUpdated(function (Set $set): void {
                        $set('student_id', null);
                    })
                    ->disabled(fn (?Fee $record): bool => filled($record))
                    ->dehydrated(false),
                Select::make('student_id')
                    ->label(__('filament.fees.fields.student'))
                    ->options(fn (Get $get): array => Student::query()
                        ->when(
                            $get('classroom_filter'),
                            fn ($query, $classroomId) => $query->where('classroom_id', $classroomId),
                        )
                        ->orderBy('full_name')
                        ->get()
                        ->mapWithKeys(fn (Student $student): array => [
                            $student->id => trim(sprintf(
                                '%s%s',
                                $student->student_number ? $student->student_number . ' • ' : '',
                                $student->full_name,
                            )),
                        ])
                        ->all())
                    ->searchable()
                    ->native(false)
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set, Get $get, ?Fee $record, ?string $state): void {
                        if (blank($state)) {
                            $set('scholarship_name_display', __('filament.fees.scholarships.none'));
                            $set('scholarship_discount_display', __('filament.fees.scholarships.none'));
                            $set('selected_scholarship_type', null);
                            $set('selected_scholarship_value', null);

                            return;
                        }

                        $student = Student::query()
                            ->with('activeScholarships')
                            ->find($state);

                        if ($student === null || $student->activeScholarships->isEmpty()) {
                            $set('scholarship_name_display', __('filament.fees.scholarships.none'));
                            $set('scholarship_discount_display', __('filament.fees.scholarships.none'));
                            $set('selected_scholarship_type', null);
                            $set('selected_scholarship_value', null);

                            return;
                        }

                        $scholarship = $student->activeScholarships->first();
                        $set('scholarship_name_display', $scholarship?->name ?? __('filament.fees.scholarships.none'));
                        $set('scholarship_discount_display', self::formatScholarshipDiscountLabel($scholarship));
                        $set('selected_scholarship_type', $scholarship?->type->value);
                        $set('selected_scholarship_value', $scholarship?->amount);
                        self::updateSummaries($set, $get, $record);
                    })
                    ->disabled(fn (?Fee $record): bool => filled($record)),
            ]);
    }

    private static function getBillingSection(): Section
    {
        return Section::make(__('filament.fees.sections.billing'))
            ->columns(2)
            ->schema([
                Hidden::make('selected_scholarship_type')
                    ->afterStateHydrated(function (Hidden $component, ?Fee $record): void {
                        $scholarship = self::resolveScholarshipForRecord($record);

                        $component->state($scholarship?->type?->value);
                    })
                    ->dehydrated(false),
                Hidden::make('selected_scholarship_value')
                    ->afterStateHydrated(function (Hidden $component, ?Fee $record): void {
                        $scholarship = self::resolveScholarshipForRecord($record);

                        $component->state($scholarship?->amount);
                    })
                    ->dehydrated(false),
                TextInput::make('amount')
                    ->label(__('filament.fees.fields.amount'))
                    ->numeric()
                    ->minValue(0)
                    ->prefix('IDR')
                    ->required()
                    ->afterStateHydrated(function (TextInput $component, ?Fee $record): void {
                        if ($record === null) {
                            return;
                        }

                        $baseAmount = $record->metadata['base_amount'] ?? null;

                        if ($baseAmount !== null) {
                            $component->state($baseAmount);
                        }
                    })
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, Get $get, ?Fee $record) => self::updateSummaries($set, $get, $record)),
                TextInput::make('currency')
                    ->label(__('filament.fees.fields.currency'))
                    ->default('IDR')
                    ->maxLength(3)
                    ->readOnly()
                    ->required(),
                TextInput::make('scholarship_name_display')
                    ->label(__('filament.fees.fields.scholarship'))
                    ->afterStateHydrated(function (TextInput $component, ?Fee $record): void {
                        $scholarship = self::resolveScholarshipForRecord($record);

                        $component->state($scholarship?->name ?? __('filament.fees.scholarships.none'));
                    })
                    ->disabled()
                    ->dehydrated(false),
                TextInput::make('scholarship_discount_display')
                    ->label(__('filament.fees.fields.scholarship_discount'))
                    ->afterStateHydrated(function (TextInput $component, ?Fee $record): void {
                        $scholarship = self::resolveScholarshipForRecord($record);

                        $component->state(self::formatScholarshipDiscountLabel($scholarship));
                    })
                    ->disabled()
                    ->dehydrated(false),
                Checkbox::make('apply_scholarship')
                    ->label(__('filament.fees.fields.apply_scholarship'))
                    ->default(fn (?Fee $record): bool => (bool) ($record?->metadata['apply_scholarship'] ?? ($record?->scholarship_id !== null)))
                    ->afterStateHydrated(function (Checkbox $component, ?Fee $record): void {
                        if ($record === null) {
                            return;
                        }

                        $state = (bool) ($record->metadata['apply_scholarship'] ?? ($record->scholarship_id !== null));
                        $component->state($state);
                    })
                    ->live()
                    ->afterStateUpdated(fn (Set $set, Get $get, ?Fee $record) => self::updateSummaries($set, $get, $record)),
                TextInput::make('paid_amount')
                    ->label(__('filament.fees.fields.paid_amount'))
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->prefix('IDR')
                    ->live()
                    ->afterStateUpdated(fn (Set $set, Get $get, ?Fee $record) => self::updateSummaries($set, $get, $record)),
                TextInput::make('net_amount_display')
                    ->label(__('filament.fees.fields.net_amount'))
                    ->disabled()
                    ->dehydrated(false)
                    ->afterStateHydrated(function (TextInput $component, ?Fee $record): void {
                        if ($record === null) {
                            return;
                        }

                        $amount = max(
                            (float) ($record->metadata['base_amount'] ?? $record->amount ?? 0)
                            - (float) ($record->scholarship_discount_amount ?? 0),
                            0,
                        );

                        $component->state(Number::currency($amount, $record->currency ?? 'IDR'));
                    }),
                TextInput::make('outstanding_amount_display')
                    ->label(__('filament.fees.fields.outstanding_amount'))
                    ->disabled()
                    ->dehydrated(false)
                    ->afterStateHydrated(function (TextInput $component, ?Fee $record): void {
                        if ($record === null) {
                            return;
                        }

                        $net = max(
                            (float) ($record->metadata['base_amount'] ?? $record->amount ?? 0)
                            - (float) ($record->scholarship_discount_amount ?? 0),
                            0,
                        );

                        $outstanding = max($net - (float) ($record->paid_amount ?? 0), 0);
                        $component->state(Number::currency($outstanding, $record->currency ?? 'IDR'));
                    }),
            ]);
    }

    private static function getScheduleSection(): Section
    {
        return Section::make(__('filament.fees.fields.due_date'))
            ->columns(2)
            ->schema([
                DatePicker::make('due_date')
                    ->label(__('filament.fees.fields.due_date'))
                    ->native(false),
                Select::make('status')
                    ->label(__('filament.fees.fields.status'))
                    ->options(FeeStatus::options())
                    ->enum(FeeStatus::class)
                    ->default(FeeStatus::Pending->value)
                    ->native(false)
                    ->required()
                    ->disabled(),
                DatePicker::make('paid_at')
                    ->label(__('filament.fees.fields.paid_at'))
                    ->native(false),
            ]);
    }

    private static function getMetaSection(): Section
    {
        return Section::make(__('filament.fees.fields.description'))
            ->schema([
                Textarea::make('description')
                    ->label(__('filament.fees.fields.description'))
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }

    private static function calculateScholarshipDiscount(Get $get, ?Fee $record, float $base): float
    {
        $apply = filter_var(
            $get('apply_scholarship')
            ?? ($record?->metadata['apply_scholarship'] ?? ($record?->scholarship_id !== null)),
            FILTER_VALIDATE_BOOLEAN,
        );

        if (! $apply || $base <= 0) {
            return 0.0;
        }

        $type = $get('selected_scholarship_type') ?? $record?->scholarship?->type?->value;
        $value = $get('selected_scholarship_value') ?? $record?->scholarship?->amount;

        if ($type === null || $value === null) {
            return (float) ($record?->scholarship_discount_amount ?? 0);
        }

        $value = (float) $value;

        if ($type === ScholarshipType::Percentage->value) {
            return min($base, ($base * $value) / 100);
        }

        return min($base, $value);
    }

    private static function updateSummaries(Set $set, Get $get, ?Fee $record): void
    {
        $base = (float) ($get('amount') ?? $record?->metadata['base_amount'] ?? $record?->amount ?? 0);
        $currency = $get('currency') ?? $record?->currency ?? 'IDR';
        $discount = self::calculateScholarshipDiscount($get, $record, $base);
        $paid = (float) ($get('paid_amount') ?? $record?->paid_amount ?? 0);

        $net = max($base - $discount, 0);
        $outstanding = max($net - $paid, 0);

        $set('net_amount_display', Number::currency($net, $currency));
        $set('outstanding_amount_display', Number::currency($outstanding, $currency));
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public static function mutateSubmissionData(array $data, ?Fee $record = null): array
    {
        $baseAmount = (float) ($data['amount'] ?? $record?->metadata['base_amount'] ?? $record?->amount ?? 0);
        $metadata = $record?->metadata;

        if (! is_array($metadata)) {
            $metadata = [];
        }

        $applyScholarship = filter_var(
            $data['apply_scholarship']
            ?? ($record?->metadata['apply_scholarship'] ?? ($record?->scholarship_id !== null)),
            FILTER_VALIDATE_BOOLEAN,
        );

        $metadata['base_amount'] = $baseAmount;
        $metadata['apply_scholarship'] = $applyScholarship;
        $data['metadata'] = $metadata;

        $studentId = $data['student_id'] ?? $record?->student_id;
        $studentId = $studentId !== null ? (int) $studentId : null;
        $scholarship = self::resolveScholarshipForRecord($record, $studentId);

        $data['scholarship_id'] = $scholarship?->id;
        $data['scholarship_discount_amount'] = 0.0;
        $data['scholarship_discount_percent'] = null;

        if ($applyScholarship && $scholarship !== null && $baseAmount > 0) {
            $value = (float) $scholarship->amount;

            if ($scholarship->type === ScholarshipType::Percentage) {
                $percent = (int) round(min(100, max(0, $value)));
                $discount = ($baseAmount * $percent) / 100;
                $data['scholarship_discount_percent'] = $percent;
            } else {
                $discount = min($baseAmount, $value);
                $data['scholarship_discount_percent'] = $baseAmount > 0
                    ? (int) round(($discount / $baseAmount) * 100)
                    : null;
            }

            $data['scholarship_discount_amount'] = $discount;
        }

        unset($data['apply_scholarship'], $data['classroom_filter']);

        $data['amount'] = max($baseAmount - ($data['scholarship_discount_amount'] ?? 0), 0);

        return $data;
    }

    private static function resolveScholarshipForRecord(?Fee $record, ?int $studentId = null): ?Scholarship
    {
        if ($record !== null && $record->scholarship !== null) {
            return $record->scholarship;
        }

        return self::resolveStudentScholarship($studentId ?? $record?->student_id);
    }

    private static function formatScholarshipDiscountLabel(?Scholarship $scholarship): string
    {
        if ($scholarship === null) {
            return __('filament.fees.scholarships.none');
        }

        if ($scholarship->type === ScholarshipType::Percentage) {
            return sprintf('%s (%s%%)', $scholarship->name, (int) $scholarship->amount);
        }

        return sprintf(
            '%s (IDR %s)',
            $scholarship->name,
            number_format((float) $scholarship->amount, 0),
        );
    }

    private static function resolveStudentScholarship(?int $studentId): ?Scholarship
    {
        if ($studentId === null) {
            return null;
        }

        return Student::query()
            ->with(['activeScholarships' => fn ($query) => $query
                ->orderByDesc('scholarship_student.effective_from')])
            ->find($studentId)
            ?->activeScholarships
            ->first();
    }
}
