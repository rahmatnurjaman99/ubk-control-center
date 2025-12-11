<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SystemRole;
use Guava\Calendar\Contracts\Eventable;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Student;
use App\Models\Staff;
use App\Models\Guardian;

class Schedule extends Model implements Eventable
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'subject_id',
        'classroom_id',
        'staff_id',
        'academic_year_id',
        'starts_at',
        'ends_at',
        'is_all_day',
        'location',
        'description',
        'color',
        'metadata',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_all_day' => 'boolean',
            'metadata' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $schedule): void {
            $schedule->validateChronology();
            $schedule->guardConflicts();
        });
    }

    public function scopeVisibleTo(Builder $query, ?User $user = null): Builder
    {
        $user ??= auth()->user();

        if ($user === null) {
            return $query->whereRaw('0 = 1');
        }

        if ($user->hasAnyRole([
            SystemRole::SuperAdmin->value,
            SystemRole::Admin->value,
            SystemRole::PanelUser->value,
        ])) {
            return $query;
        }

        if ($user->hasRole(SystemRole::Teacher->value)) {
            $staffId = Staff::query()
                ->where('user_id', $user->id)
                ->value('id');

            if ($staffId === null) {
                return $query->whereRaw('0 = 1');
            }

            return $query->where('staff_id', $staffId);
        }

        if ($user->hasRole(SystemRole::Student->value)) {
            $student = Student::query()
                ->where('user_id', $user->id)
                ->first();

            if ($student === null) {
                return $query->whereRaw('0 = 1');
            }

            return $query->where(function (Builder $builder) use ($student): void {
                $builder->where('classroom_id', $student->classroom_id)
                    ->orWhereNull('classroom_id');
            });
        }

        if ($user->hasRole(SystemRole::Guardian->value)) {
            $guardian = Guardian::query()
                ->with(['students' => fn ($relation) => $relation->whereNotNull('classroom_id')])
                ->where('user_id', $user->id)
                ->first();

            if ($guardian === null || $guardian->students->isEmpty()) {
                return $query->whereRaw('0 = 1');
            }

            $classroomIds = $guardian->students
                ->pluck('classroom_id')
                ->filter()
                ->unique()
                ->all();

            return $query->where(function (Builder $builder) use ($classroomIds): void {
                $builder->whereIn('classroom_id', $classroomIds)
                    ->orWhereNull('classroom_id');
            });
        }

        return $query;
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function toCalendarEvent(): CalendarEvent
    {
        $title = $this->title ?: $this->subject?->name ?: __('filament.schedules.defaults.title');

        $event = CalendarEvent::make($this)
            ->title($title)
            ->start($this->starts_at ?? Carbon::now())
            ->end($this->ends_at ?? Carbon::now()->addHour())
            ->allDay($this->is_all_day)
            ->extendedProps([
                'subject' => $this->subject?->name,
                'classroom' => $this->classroom?->name,
                'teacher' => $this->teacher?->staff_name,
                'location' => $this->location,
                'description' => $this->description,
            ]);

        if ($this->color) {
            $event->backgroundColor($this->color);
        }

        return $event;
    }

    private function validateChronology(): void
    {
        if (
            $this->starts_at instanceof Carbon
            && $this->ends_at instanceof Carbon
            && $this->ends_at->lessThanOrEqualTo($this->starts_at)
        ) {
            throw ValidationException::withMessages([
                'ends_at' => __('filament.schedules.validation.time_order'),
            ]);
        }
    }

    private function guardConflicts(): void
    {
        if ($this->classroom_id === null && $this->staff_id === null) {
            return;
        }

        if (! ($this->starts_at instanceof Carbon) || ! ($this->ends_at instanceof Carbon)) {
            return;
        }

        $conflict = self::query()
            ->when($this->exists, fn (Builder $builder): Builder => $builder->whereKeyNot($this->getKey()))
            ->where(function (Builder $builder): void {
                if ($this->classroom_id !== null) {
                    $builder->where('classroom_id', $this->classroom_id);
                }

                if ($this->staff_id !== null) {
                    $method = $this->classroom_id !== null ? 'orWhere' : 'where';
                    $builder->{$method}('staff_id', $this->staff_id);
                }
            })
            ->where('starts_at', '<', $this->ends_at)
            ->where('ends_at', '>', $this->starts_at)
            ->with(['subject', 'classroom'])
            ->first();

        if (! $conflict instanceof self) {
            return;
        }

        $message = __('filament.schedules.validation.conflict', [
            'title' => $conflict->title,
            'start' => $conflict->starts_at?->format('M d, H:i'),
            'end' => $conflict->ends_at?->format('M d, H:i'),
            'classroom' => $conflict->classroom?->name ?? '-',
        ]);

        throw ValidationException::withMessages([
            'starts_at' => $message,
        ]);
    }
}
