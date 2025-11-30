<?php

declare(strict_types=1);

namespace App\Filament\Resources\RegistrationIntakes\Pages;

use App\Enums\RegistrationStatus;
use App\Enums\StudentStatus;
use App\Enums\TransactionType;
use App\Filament\Resources\RegistrationIntakes\RegistrationIntakeResource;
use App\Models\Classroom;
use App\Models\ClassroomAssignment;
use App\Models\Guardian;
use App\Models\RegistrationIntake;
use App\Models\Student;
use App\Models\Transaction;
use Filament\Actions;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class EditRegistrationIntake extends EditRecord
{
    protected static string $resource = RegistrationIntakeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('convert')
                ->label(__('filament.registration_intakes.actions.convert'))
                ->color('success')
                ->icon(Heroicon::OutlinedUserPlus)
                ->visible(fn(RegistrationIntake $record): bool => $record->student_id === null && $record->status !== RegistrationStatus::Completed)
                ->requiresConfirmation()
                ->action(fn(): mixed => $this->convertIntake()),
            Actions\Action::make('receipt')
                ->label(__('Print receipt'))
                ->icon('heroicon-o-printer')
                ->url(fn(): ?string => $this->getReceiptUrl())
                ->openUrlInNewTab()
                ->visible(fn(): bool => $this->getReceiptUrl() !== null),
            DeleteAction::make(),
        ];
    }

    /**
     * @throws ValidationException
     */
    private function convertIntake(): void
    {
        $record = $this->getRecord();

        try {
            DB::transaction(function () use ($record): void {
                $guardian = $this->resolveGuardian($record);

                $classroom = $record->classroom;

                if ($classroom !== null) {
                    $this->ensureClassroomHasCapacity($classroom);
                }

                $student = Student::create([
                    'guardian_id' => $guardian->id,
                    'academic_year_id' => $record->academic_year_id,
                    'classroom_id' => $classroom?->id,
                    'student_number' => $this->generateStudentNumber(),
                    'full_name' => $record->student_full_name,
                    'date_of_birth' => $record->student_date_of_birth,
                    'gender' => $record->student_gender,
                    'status' => StudentStatus::Active,
                    'enrolled_on' => now()->toDateString(),
                    'legacy_reference' => $record->form_number,
                ]);

                if ($classroom !== null) {
                    ClassroomAssignment::create([
                        'student_id' => $student->id,
                        'classroom_id' => $classroom->id,
                        'academic_year_id' => $record->academic_year_id,
                        'grade_level' => $classroom->grade_level?->value,
                        'assigned_on' => now()->toDateString(),
                        'notes' => __('filament.registration_intakes.actions.assignment_note', ['form' => $record->form_number]),
                    ]);
                }

                $this->createTransaction($record);

                $record->update([
                    'student_id' => $student->id,
                    'status' => RegistrationStatus::Completed,
                    'processed_at' => now(),
                    'processed_by' => Auth::id(),
                ]);
            });
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $throwable) {
            report($throwable);

            Notification::make()
                ->title(__('filament.registration_intakes.actions.convert_failed'))
                ->body($throwable->getMessage())
                ->danger()
                ->send();

            return;
        }

        Notification::make()
            ->title(__('filament.registration_intakes.actions.convert_success'))
            ->success()
            ->send();

        $this->fillForm();
    }

    private function ensureClassroomHasCapacity(Classroom $classroom): void
    {
        if ($classroom->capacity === null) {
            return;
        }

        $currentCount = $classroom->students()->count();

        if ($currentCount >= $classroom->capacity) {
            throw ValidationException::withMessages([
                'classroom_id' => __('filament.registration_intakes.validation.classroom_full', [
                    'classroom' => $classroom->name,
                ]),
            ]);
        }
    }

    private function resolveGuardian(RegistrationIntake $record): Guardian
    {
        $guardian = Guardian::query()
            ->when($record->guardian_phone, fn($query, $phone) => $query->where('phone', $phone))
            ->when($record->guardian_email, fn($query, $email) => $query->orWhere('email', $email))
            ->first();

        if ($guardian instanceof Guardian) {
            return $guardian;
        }

        return Guardian::create([
            'guardian_number' => $this->generateGuardianNumber(),
            'full_name' => $record->guardian_name,
            'relationship' => 'parent',
            'phone' => $record->guardian_phone,
            'email' => $record->guardian_email,
            'address' => $record->guardian_address,
            'legacy_reference' => $record->form_number,
        ]);
    }

    private function generateGuardianNumber(): string
    {
        do {
            $number = 'GRD-' . Str::upper(Str::random(6));
        } while (Guardian::withTrashed()->where('guardian_number', $number)->exists());

        return $number;
    }

    private function generateStudentNumber(): string
    {
        do {
            $number = 'STD-' . Str::upper(Str::random(6));
        } while (Student::withTrashed()->where('student_number', $number)->exists());

        return $number;
    }

    private function createTransaction(RegistrationIntake $record): void
    {
        if ($record->payment_amount <= 0) {
            return;
        }

        $transaction = Transaction::create([
                    'reference' => Transaction::generateReference(),
                    'label' => __('Registration fee :form', ['form' => $record->form_number]),
                    'type' => TransactionType::Income,
                    'category' => 'registration',
                    'payment_status' => $record->payment_received_at !== null ? 'paid' : 'pending',
                    'payment_method' => $record->payment_method,
                    'amount' => $record->payment_amount,
                    'currency' => 'IDR',
                    'due_date' => $record->payment_received_at,
                    'paid_at' => $record->payment_received_at,
                    'academic_year_id' => $record->academic_year_id,
            'counterparty_name' => $record->guardian_name,
            'recorded_by' => Auth::id(),
            'notes' => __('Created from registration intake :form', ['form' => $record->form_number]),
            'metadata' => [
                'intake_id' => $record->id,
                'payment_reference' => $record->payment_reference,
            ],
        ]);

        $record->update([
            'payment_reference' => $transaction->reference,
        ]);
    }

    private function getReceiptUrl(): ?string
    {
        $transaction = Transaction::query()
            ->whereJsonContains('metadata->intake_id', $this->getRecord()->id)
            ->orWhere('reference', $this->getRecord()->payment_reference)
            ->latest('id')
            ->first();

        return $transaction?->exists ? route('receipts.transactions.show', $transaction) : null;
    }
}
