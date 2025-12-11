<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\AssignmentRole;
use App\Enums\AttendanceStatus;
use App\Enums\EducationLevel;
use App\Enums\FeeStatus;
use App\Enums\FeeType;
use App\Enums\GradeLevel;
use App\Enums\PaymentStatus;
use App\Enums\RegistrationStatus;
use App\Enums\SchoolLevel;
use App\Enums\StaffRole;
use App\Enums\StudentStatus;
use App\Enums\SystemRole;
use App\Enums\TransactionType;
use App\Enums\UserStatus;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\ClassroomAssignment;
use App\Models\ClassroomStaff;
use App\Models\Fee;
use App\Models\FeeTemplate;
use App\Models\Guardian;
use App\Models\RegistrationIntake;
use App\Models\RegistrationIntakeDocument;
use App\Models\Schedule;
use App\Models\Staff;
use App\Models\StaffAttendance;
use App\Models\StaffDocument;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Subject;
use App\Models\SubjectCategory;
use App\Models\Transaction;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DefaultDataSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * @var array<string, array<string, mixed>>
     */
    private const CLASSROOM_BLUEPRINTS = [
        'paud' => [
            'code' => 'CLS-PAUD-PLAY',
            'name' => 'PAUD Playgroup',
            'capacity' => 16,
            'students' => 14,
            'homeroom' => 'STF-1001',
        ],
        'tk_a' => [
            'code' => 'CLS-TKA-ANGGREK',
            'name' => 'TK A - Anggrek',
            'capacity' => 18,
            'students' => 16,
            'homeroom' => 'STF-1002',
        ],
        'tk_b' => [
            'code' => 'CLS-TKB-MELATI',
            'name' => 'TK B - Melati',
            'capacity' => 18,
            'students' => 16,
            'homeroom' => 'STF-1003',
        ],
        'sd_1' => [
            'code' => 'CLS-SD1-A',
            'name' => 'SD 1 - A',
            'capacity' => 26,
            'students' => 22,
            'homeroom' => 'STF-2001',
        ],
        'sd_2' => [
            'code' => 'CLS-SD2-A',
            'name' => 'SD 2 - A',
            'capacity' => 26,
            'students' => 22,
            'homeroom' => 'STF-2002',
        ],
        'sd_3' => [
            'code' => 'CLS-SD3-A',
            'name' => 'SD 3 - A',
            'capacity' => 26,
            'students' => 22,
            'homeroom' => 'STF-2003',
        ],
        'sd_4' => [
            'code' => 'CLS-SD4-A',
            'name' => 'SD 4 - A',
            'capacity' => 28,
            'students' => 24,
            'homeroom' => 'STF-3001',
        ],
        'sd_5' => [
            'code' => 'CLS-SD5-A',
            'name' => 'SD 5 - A',
            'capacity' => 28,
            'students' => 24,
            'homeroom' => 'STF-3002',
        ],
        'sd_6' => [
            'code' => 'CLS-SD6-A',
            'name' => 'SD 6 - A',
            'capacity' => 28,
            'students' => 23,
            'homeroom' => 'STF-3003',
        ],
    ];

    /**
     * @var array<int, array<string, mixed>>
     */
    private const STAFF_BLUEPRINTS = [
        [
            'staff_number' => 'STF-0001',
            'staff_name' => 'Alya Pratama',
            'email' => 'alya.pratama@ubk.local',
            'role' => StaffRole::Principal,
            'system_roles' => [SystemRole::Admin],
            'education_level' => EducationLevel::Master,
            'education_institution' => 'Universitas Nusantara',
            'joined_year' => 2016,
            'graduated_year' => 2011,
            'phone' => '+62-811-555-0001',
            'emergency_contact' => 'Irwan Pratama',
            'emergency_phone' => '+62-811-333-0001',
        ],
        [
            'staff_number' => 'STF-0002',
            'staff_name' => 'Bagus Santoso',
            'email' => 'bagus.santoso@ubk.local',
            'role' => StaffRole::VicePrincipal,
            'system_roles' => [SystemRole::Admin],
            'education_level' => EducationLevel::Master,
            'education_institution' => 'Universitas Negeri Medan',
            'joined_year' => 2017,
            'graduated_year' => 2012,
            'phone' => '+62-811-555-0002',
            'emergency_contact' => 'Ratna Santoso',
            'emergency_phone' => '+62-811-333-0002',
        ],
        [
            'staff_number' => 'STF-0003',
            'staff_name' => 'Claudia Wibowo',
            'email' => 'finance@ubk.local',
            'role' => StaffRole::Accountant,
            'system_roles' => [SystemRole::PanelUser],
            'education_level' => EducationLevel::Bachelor,
            'education_institution' => 'Universitas Indonesia',
            'joined_year' => 2018,
            'graduated_year' => 2014,
            'phone' => '+62-811-555-0003',
            'emergency_contact' => 'Yohanes Wibowo',
            'emergency_phone' => '+62-811-333-0003',
        ],
        [
            'staff_number' => 'STF-0004',
            'staff_name' => 'Dewi Anjani',
            'email' => 'dewianjani@ubk.local',
            'role' => StaffRole::Counselor,
            'system_roles' => [SystemRole::PanelUser],
            'education_level' => EducationLevel::Bachelor,
            'education_institution' => 'Universitas Katolik Parahyangan',
            'joined_year' => 2019,
            'graduated_year' => 2014,
            'phone' => '+62-811-555-0004',
            'emergency_contact' => 'Laras Anjani',
            'emergency_phone' => '+62-811-333-0004',
        ],
        [
            'staff_number' => 'STF-1001',
            'staff_name' => 'Eka Rahardi',
            'email' => 'eka.rahardi@ubk.local',
            'role' => StaffRole::Teacher,
            'system_roles' => [SystemRole::Teacher],
            'education_level' => EducationLevel::Bachelor,
            'education_institution' => 'UNJ',
            'joined_year' => 2019,
            'graduated_year' => 2015,
            'phone' => '+62-811-555-1001',
            'emergency_contact' => 'Rani Rahardi',
            'emergency_phone' => '+62-811-333-1001',
        ],
        [
            'staff_number' => 'STF-1002',
            'staff_name' => 'Farah Silitonga',
            'email' => 'farah.silitonga@ubk.local',
            'role' => StaffRole::Teacher,
            'system_roles' => [SystemRole::Teacher],
            'education_level' => EducationLevel::Bachelor,
            'education_institution' => 'Universitas Negeri Yogyakarta',
            'joined_year' => 2019,
            'graduated_year' => 2015,
            'phone' => '+62-811-555-1002',
            'emergency_contact' => 'Andre Silitonga',
            'emergency_phone' => '+62-811-333-1002',
        ],
        [
            'staff_number' => 'STF-1003',
            'staff_name' => 'Gilang Prakoso',
            'email' => 'gilang.prakoso@ubk.local',
            'role' => StaffRole::Teacher,
            'system_roles' => [SystemRole::Teacher],
            'education_level' => EducationLevel::Bachelor,
            'education_institution' => 'Universitas Negeri Surabaya',
            'joined_year' => 2020,
            'graduated_year' => 2016,
            'phone' => '+62-811-555-1003',
            'emergency_contact' => 'Nadia Prakoso',
            'emergency_phone' => '+62-811-333-1003',
        ],
        [
            'staff_number' => 'STF-2001',
            'staff_name' => 'Hannah Sutanto',
            'email' => 'hannah.sutanto@ubk.local',
            'role' => StaffRole::Teacher,
            'system_roles' => [SystemRole::Teacher],
            'education_level' => EducationLevel::Bachelor,
            'education_institution' => 'Universitas Kristen Satya Wacana',
            'joined_year' => 2018,
            'graduated_year' => 2014,
            'phone' => '+62-811-555-2001',
            'emergency_contact' => 'Vincent Sutanto',
            'emergency_phone' => '+62-811-333-2001',
        ],
        [
            'staff_number' => 'STF-2002',
            'staff_name' => 'Indra Mahesa',
            'email' => 'indra.mahesa@ubk.local',
            'role' => StaffRole::Teacher,
            'system_roles' => [SystemRole::Teacher],
            'education_level' => EducationLevel::Bachelor,
            'education_institution' => 'Universitas Padjadjaran',
            'joined_year' => 2018,
            'graduated_year' => 2013,
            'phone' => '+62-811-555-2002',
            'emergency_contact' => 'Kania Mahesa',
            'emergency_phone' => '+62-811-333-2002',
        ],
        [
            'staff_number' => 'STF-2003',
            'staff_name' => 'Jihan Maharani',
            'email' => 'jihan.maharani@ubk.local',
            'role' => StaffRole::Teacher,
            'system_roles' => [SystemRole::Teacher],
            'education_level' => EducationLevel::Bachelor,
            'education_institution' => 'Universitas Negeri Semarang',
            'joined_year' => 2018,
            'graduated_year' => 2013,
            'phone' => '+62-811-555-2003',
            'emergency_contact' => 'Rendy Maharani',
            'emergency_phone' => '+62-811-333-2003',
        ],
        [
            'staff_number' => 'STF-3001',
            'staff_name' => 'Kelvin Wicaksono',
            'email' => 'kelvin.wicaksono@ubk.local',
            'role' => StaffRole::Teacher,
            'system_roles' => [SystemRole::Teacher],
            'education_level' => EducationLevel::Bachelor,
            'education_institution' => 'Universitas Sanata Dharma',
            'joined_year' => 2017,
            'graduated_year' => 2012,
            'phone' => '+62-811-555-3001',
            'emergency_contact' => 'Selvi Wicaksono',
            'emergency_phone' => '+62-811-333-3001',
        ],
        [
            'staff_number' => 'STF-3002',
            'staff_name' => 'Laras Ayu',
            'email' => 'laras.ayu@ubk.local',
            'role' => StaffRole::Teacher,
            'system_roles' => [SystemRole::Teacher],
            'education_level' => EducationLevel::Bachelor,
            'education_institution' => 'Universitas Negeri Jakarta',
            'joined_year' => 2017,
            'graduated_year' => 2012,
            'phone' => '+62-811-555-3002',
            'emergency_contact' => 'Sri Ayu',
            'emergency_phone' => '+62-811-333-3002',
        ],
        [
            'staff_number' => 'STF-3003',
            'staff_name' => 'Mahendra Yudha',
            'email' => 'mahendra.yudha@ubk.local',
            'role' => StaffRole::Teacher,
            'system_roles' => [SystemRole::Teacher],
            'education_level' => EducationLevel::Bachelor,
            'education_institution' => 'Universitas Sebelas Maret',
            'joined_year' => 2016,
            'graduated_year' => 2011,
            'phone' => '+62-811-555-3003',
            'emergency_contact' => 'Nova Yudha',
            'emergency_phone' => '+62-811-333-3003',
        ],
    ];

    /**
     * @var array<int, array<string, string>>
     */
    private const GUARDIAN_BLUEPRINTS = [
        ['guardian_number' => 'GRD-0001', 'full_name' => 'Andi Pratama', 'email' => 'andi.pratama@families.local', 'phone' => '+62-812-1000-0001', 'relationship' => 'parent', 'occupation' => 'Arsitek', 'address' => 'Jl. Cempaka No. 8, Bandung'],
        ['guardian_number' => 'GRD-0002', 'full_name' => 'Bunga Setiono', 'email' => 'bunga.setiono@families.local', 'phone' => '+62-812-1000-0002', 'relationship' => 'parent', 'occupation' => 'Dokter', 'address' => 'Jl. Gandaria No. 12, Jakarta Selatan'],
        ['guardian_number' => 'GRD-0003', 'full_name' => 'Chairul Prakoso', 'email' => 'chairul.prakoso@families.local', 'phone' => '+62-812-1000-0003', 'relationship' => 'parent', 'occupation' => 'Wiraswasta', 'address' => 'Jl. Palapa No. 21, Bekasi'],
        ['guardian_number' => 'GRD-0004', 'full_name' => 'Dina Chairani', 'email' => 'dina.chairani@families.local', 'phone' => '+62-812-1000-0004', 'relationship' => 'parent', 'occupation' => 'Akuntan', 'address' => 'Jl. Rawamangun No. 17, Jakarta'],
        ['guardian_number' => 'GRD-0005', 'full_name' => 'Edo Mahardika', 'email' => 'edo.mahardika@families.local', 'phone' => '+62-812-1000-0005', 'relationship' => 'parent', 'occupation' => 'Insinyur', 'address' => 'Jl. Gajah Mada No. 88, Surabaya'],
        ['guardian_number' => 'GRD-0006', 'full_name' => 'Fina Lestari', 'email' => 'fina.lestari@families.local', 'phone' => '+62-812-1000-0006', 'relationship' => 'parent', 'occupation' => 'Guru', 'address' => 'Jl. Melati No. 3, Depok'],
        ['guardian_number' => 'GRD-0007', 'full_name' => 'Gatra Wirawan', 'email' => 'gatra.wirawan@families.local', 'phone' => '+62-812-1000-0007', 'relationship' => 'parent', 'occupation' => 'Konsultan', 'address' => 'Jl. Rajawali No. 5, Tangerang'],
        ['guardian_number' => 'GRD-0008', 'full_name' => 'Hani Permata', 'email' => 'hani.permata@families.local', 'phone' => '+62-812-1000-0008', 'relationship' => 'parent', 'occupation' => 'Psikolog', 'address' => 'Jl. Pahlawan No. 9, Bandung'],
        ['guardian_number' => 'GRD-0009', 'full_name' => 'Indra Syahputra', 'email' => 'indra.syahputra@families.local', 'phone' => '+62-812-1000-0009', 'relationship' => 'parent', 'occupation' => 'Manajer Operasional', 'address' => 'Jl. Agung No. 11, Batam'],
        ['guardian_number' => 'GRD-0010', 'full_name' => 'Julianti Siregar', 'email' => 'julianti.siregar@families.local', 'phone' => '+62-812-1000-0010', 'relationship' => 'parent', 'occupation' => 'Perawat', 'address' => 'Jl. Cendrawasih No. 4, Medan'],
        ['guardian_number' => 'GRD-0011', 'full_name' => 'Kevin Hartono', 'email' => 'kevin.hartono@families.local', 'phone' => '+62-812-1000-0011', 'relationship' => 'parent', 'occupation' => 'Pengacara', 'address' => 'Jl. Kemang No. 10, Jakarta'],
        ['guardian_number' => 'GRD-0012', 'full_name' => 'Lala Azzahra', 'email' => 'lala.azzahra@families.local', 'phone' => '+62-812-1000-0012', 'relationship' => 'parent', 'occupation' => 'Apoteker', 'address' => 'Jl. Kenanga No. 2, Bogor'],
    ];

    /**
     * @var array<int, array<string, mixed>>
     */
    private const SUBJECT_CATEGORY_BLUEPRINTS = [
        ['name' => 'Core Subjects', 'slug' => 'core-subjects', 'description' => 'Mathematics, science, and foundational competencies.'],
        ['name' => 'Language & Communication', 'slug' => 'language-communication', 'description' => 'Mother tongue, English, and literacy.'],
        ['name' => 'Creative & Character', 'slug' => 'creative-character', 'description' => 'Arts, PE, and character development.'],
    ];

    /**
     * @var array<int, array<string, mixed>>
     */
    private const SUBJECT_BLUEPRINTS = [
        ['code' => 'PAUD-CIRCLE', 'name' => 'Morning Circle', 'level' => SchoolLevel::Paud, 'category' => 'core-subjects', 'hours' => 2, 'description' => 'Social readiness and routines.'],
        ['code' => 'PAUD-STORY', 'name' => 'Story Time', 'level' => SchoolLevel::Paud, 'category' => 'language-communication', 'hours' => 2, 'description' => 'Listening and speaking through stories.'],
        ['code' => 'TK-NUM', 'name' => 'Early Numeracy', 'level' => SchoolLevel::Tk, 'category' => 'core-subjects', 'hours' => 2, 'description' => 'Number recognition and counting games.'],
        ['code' => 'TK-ART', 'name' => 'Movement & Art', 'level' => SchoolLevel::Tk, 'category' => 'creative-character', 'hours' => 2, 'description' => 'Fine motor and creativity lab.'],
        ['code' => 'SD-MATH', 'name' => 'Mathematics', 'level' => SchoolLevel::Sd, 'category' => 'core-subjects', 'hours' => 3, 'description' => 'Competency-based mathematics.'],
        ['code' => 'SD-BI', 'name' => 'Bahasa Indonesia', 'level' => SchoolLevel::Sd, 'category' => 'language-communication', 'hours' => 3, 'description' => 'Reading and writing mastery.'],
        ['code' => 'SD-ENG', 'name' => 'English Conversation', 'level' => SchoolLevel::Sd, 'category' => 'language-communication', 'hours' => 2, 'description' => 'Basic conversation and vocabulary.'],
        ['code' => 'SD-SCI', 'name' => 'Science & Discovery', 'level' => SchoolLevel::Sd, 'category' => 'core-subjects', 'hours' => 3, 'description' => 'Hands-on experiments.'],
        ['code' => 'SD-ART', 'name' => 'Art & Character Club', 'level' => SchoolLevel::Sd, 'category' => 'creative-character', 'hours' => 2, 'description' => 'Civic, arts, and leadership projects.'],
    ];

    /**
     * @var array<int, string>
     */
    private const STUDENT_FIRST_NAMES = ['Adi', 'Alya', 'Bagas', 'Citra', 'Davin', 'Eka', 'Farhan', 'Gita', 'Hana', 'Indra', 'Jaya', 'Kirana', 'Lintang', 'Mahesa', 'Nadia', 'Pandu'];

    /**
     * @var array<int, string>
     */
    private const STUDENT_LAST_NAMES = ['Pratama', 'Nugraha', 'Wibowo', 'Hartanto', 'Siregar', 'Putri', 'Yulianto', 'Saputra', 'Wijaya', 'Sari', 'Ramadhan', 'Syahputra', 'Pangestu', 'Mahardika', 'Lesmana', 'Utami'];

    /**
     * @var array<string, int>
     */
    private const TUITION_RATES = [
        'paud' => 350_000,
        'tk_a' => 420_000,
        'tk_b' => 450_000,
        'sd_1' => 900_000,
        'sd_2' => 950_000,
        'sd_3' => 1_000_000,
        'sd_4' => 1_050_000,
        'sd_5' => 1_150_000,
        'sd_6' => 1_200_000,
    ];

    private ?int $principalUserId = null;

    private ?int $financeUserId = null;

    /**
     * @var array<string, int>
     */
    private array $classroomStudentQuota = [];

    public function run(): void
    {
        $years = $this->seedAcademicYears();
        $categories = $this->seedSubjectCategories();
        $subjects = $this->seedSubjects($years['current'], $categories);
        $staff = $this->seedStaff();
        $this->seedStaffDocuments($staff);
        $guardians = $this->seedGuardians();
        $classrooms = $this->seedClassrooms($years['current'], $staff);
        $students = $this->seedStudents($guardians, $years['current'], $classrooms);
        $this->seedClassroomAssignments($students, $classrooms, $years['current']);
        $this->seedClassroomStaff($classrooms, $staff, $subjects, $years['current']);
        $this->seedSchedules($classrooms, $subjects, $staff, $years['current']);
        $this->seedRecurringScheduleExample($years['current'], $classrooms);
        $feeTemplates = $this->seedFeeTemplates();
        $this->seedFees($students, $years['current'], $feeTemplates);
        $this->seedAttendances($students, $staff);
        $this->seedRegistrationPipeline($years['next'], $classrooms);
    }

    /**
     * @return array{previous: AcademicYear, current: AcademicYear, next: AcademicYear}
     */
    private function seedAcademicYears(): array
    {
        $currentStart = CarbonImmutable::create(now()->year, 7, 1);
        $currentEnd = $currentStart->addYear()->subDay();
        $previousStart = $currentStart->subYear();
        $previousEnd = $previousStart->addYear()->subDay();
        $nextStart = $currentStart->addYear();
        $nextEnd = $nextStart->addYear()->subDay();

        $previous = AcademicYear::updateOrCreate(
            ['code' => sprintf('%s-%s', $previousStart->format('Y'), $previousEnd->format('Y'))],
            [
                'name' => 'Academic Year ' . $previousStart->format('Y') . '/' . $previousEnd->format('Y'),
                'starts_on' => $previousStart->toDateString(),
                'ends_on' => $previousEnd->toDateString(),
                'is_current' => false,
            ],
        );

        $current = AcademicYear::updateOrCreate(
            ['code' => sprintf('%s-%s', $currentStart->format('Y'), $currentEnd->format('Y'))],
            [
                'name' => 'Academic Year ' . $currentStart->format('Y') . '/' . $currentEnd->format('Y'),
                'starts_on' => $currentStart->toDateString(),
                'ends_on' => $currentEnd->toDateString(),
                'is_current' => true,
            ],
        );

        $next = AcademicYear::updateOrCreate(
            ['code' => sprintf('%s-%s', $nextStart->format('Y'), $nextEnd->format('Y'))],
            [
                'name' => 'Academic Year ' . $nextStart->format('Y') . '/' . $nextEnd->format('Y'),
                'starts_on' => $nextStart->toDateString(),
                'ends_on' => $nextEnd->toDateString(),
                'is_current' => false,
            ],
        );

        return [
            'previous' => $previous,
            'current' => $current,
            'next' => $next,
        ];
    }

    /**
     * @return Collection<int, SubjectCategory>
     */
    private function seedSubjectCategories(): Collection
    {
        return collect(self::SUBJECT_CATEGORY_BLUEPRINTS)
            ->map(fn (array $blueprint): SubjectCategory => SubjectCategory::updateOrCreate(
                ['slug' => $blueprint['slug']],
                [
                    'name' => $blueprint['name'],
                    'description' => $blueprint['description'],
                ],
            ));
    }

    /**
     * @param Collection<int, SubjectCategory> $categories
     * @return Collection<int, Subject>
     */
    private function seedSubjects(AcademicYear $year, Collection $categories): Collection
    {
        $categoriesBySlug = $categories->keyBy(fn (SubjectCategory $category): string => $category->slug);

        return collect(self::SUBJECT_BLUEPRINTS)
            ->map(fn (array $subject): Subject => Subject::updateOrCreate(
                ['code' => $subject['code']],
                [
                    'academic_year_id' => $year->id,
                    'subject_category_id' => $categoriesBySlug->get($subject['category'])?->id,
                    'school_level' => $subject['level'],
                    'name' => $subject['name'],
                    'is_compulsory' => true,
                    'credit_hours' => $subject['hours'],
                    'description' => $subject['description'],
                ],
            ));
    }

    /**
     * @return Collection<int, Staff>
     */
    private function seedStaff(): Collection
    {
        return collect(self::STAFF_BLUEPRINTS)
            ->map(function (array $blueprint): Staff {
                $user = $this->ensureUser($blueprint['staff_name'], $blueprint['email'], $blueprint['system_roles']);

                $joinedOn = CarbonImmutable::create($blueprint['joined_year'], 7, 10);

                $staff = Staff::updateOrCreate(
                    ['staff_number' => $blueprint['staff_number']],
                    [
                        'user_id' => $user->id,
                        'staff_name' => $blueprint['staff_name'],
                        'role' => $blueprint['role'],
                        'joined_on' => $joinedOn->toDateString(),
                        'phone' => $blueprint['phone'],
                        'education_level' => $blueprint['education_level'],
                        'education_institution' => $blueprint['education_institution'],
                        'graduated_year' => $blueprint['graduated_year'],
                        'emergency_contact_name' => $blueprint['emergency_contact'],
                        'emergency_contact_phone' => $blueprint['emergency_phone'],
                    ],
                );

                if ($blueprint['staff_number'] === 'STF-0001') {
                    $this->principalUserId = $user->id;
                }

                if ($blueprint['staff_number'] === 'STF-0003') {
                    $this->financeUserId = $user->id;
                }

                return $staff;
            })
            ->keyBy(fn (Staff $staff): string => $staff->staff_number);
    }

    /**
     * @return Collection<int, Guardian>
     */
    private function seedGuardians(): Collection
    {
        return collect(self::GUARDIAN_BLUEPRINTS)
            ->map(function (array $blueprint, int $index): Guardian {
                $user = $this->ensureUser($blueprint['full_name'], $blueprint['email'], [SystemRole::Guardian]);

                return Guardian::updateOrCreate(
                    ['guardian_number' => $blueprint['guardian_number']],
                    [
                        'user_id' => $user->id,
                        'full_name' => $blueprint['full_name'],
                        'relationship' => $blueprint['relationship'],
                        'phone' => $blueprint['phone'],
                        'email' => $blueprint['email'],
                        'occupation' => $blueprint['occupation'],
                        'address' => $blueprint['address'],
                        'legacy_reference' => 'LEG-GRD-' . Str::padLeft((string) ($index + 1), 4, '0'),
                    ],
                );
            })
            ->values();
    }

    /**
     * @param Collection<string, Staff> $staff
     * @return Collection<string, Classroom>
     */
    private function seedClassrooms(AcademicYear $year, Collection $staff): Collection
    {
        return collect(self::CLASSROOM_BLUEPRINTS)
            ->mapWithKeys(function (array $blueprint, string $grade): array {
                $this->classroomStudentQuota[$grade] = $blueprint['students'];

                return [$grade => $blueprint];
            })
            ->map(function (array $blueprint, string $grade) use ($year, $staff): Classroom {
                $gradeLevel = GradeLevel::from($grade);
                $homeroom = $staff->get($blueprint['homeroom']);

                return Classroom::updateOrCreate(
                    ['code' => $blueprint['code']],
                    [
                        'academic_year_id' => $year->id,
                        'homeroom_staff_id' => $homeroom?->id,
                        'name' => $blueprint['name'],
                        'grade_level' => $gradeLevel,
                        'school_level' => $gradeLevel->schoolLevel(),
                        'capacity' => $blueprint['capacity'],
                        'description' => 'System default class for ' . $gradeLevel->label(),
                    ],
                );
            });
    }

    /**
     * @param Collection<int, Guardian> $guardians
     * @param Collection<string, Classroom> $classrooms
     * @return Collection<int, Student>
     */
    private function seedStudents(Collection $guardians, AcademicYear $year, Collection $classrooms): Collection
    {
        $guardianList = $guardians->values();
        $guardianCount = max($guardianList->count(), 1);
        $enrolledOn = CarbonImmutable::parse($year->starts_on)->subMonth();
        $sequence = 1;

        return $classrooms
            ->values()
            ->flatMap(function (Classroom $classroom) use ($guardianList, $guardianCount, $enrolledOn, $year, &$sequence): Collection {
                $students = collect();
                $studentQuota = $this->classroomStudentQuota[$classroom->grade_level?->value ?? ''] ?? 18;

                foreach (range(1, $studentQuota) as $index) {
                    $studentNumber = sprintf(
                        'STD-%s-%03d',
                        Str::upper(str_replace('_', '', $classroom->grade_level?->value ?? 'GEN')),
                        $index,
                    );

                    $fullName = $this->buildStudentName($sequence + $index);
                    $guardianIndex = ($sequence + $index) % $guardianCount;
                    $guardian = $guardianList->get($guardianIndex) ?? $guardianList->first();
                    $studentUserId = null;

                    if ($index === 1) {
                        $email = Str::slug($fullName, '.') . '.' . strtolower($classroom->code) . '@students.ubk.local';
                        $studentUser = $this->ensureUser($fullName, $email, [SystemRole::Student]);
                        $studentUserId = $studentUser->id;
                    }

                    $student = Student::updateOrCreate(
                        ['student_number' => $studentNumber],
                        [
                            'guardian_id' => $guardian?->id,
                            'user_id' => $studentUserId,
                            'academic_year_id' => $year->id,
                            'classroom_id' => $classroom->id,
                            'full_name' => $fullName,
                            'date_of_birth' => $this->resolveBirthDate($classroom->grade_level, $sequence + $index)->toDateString(),
                            'gender' => ($sequence + $index) % 2 === 0 ? 'female' : 'male',
                            'status' => StudentStatus::Active,
                            'enrolled_on' => $enrolledOn->toDateString(),
                            'legacy_reference' => 'LEG-STD-' . Str::padLeft((string) ($sequence + $index), 4, '0'),
                        ],
                    );

                    $student->setRelation('classroom', $classroom);
                    $students->push($student);
                }

                $sequence += $studentQuota;

                return $students;
            })
            ->values();
    }

    /**
     * @param Collection<int, Student> $students
     * @param Collection<string, Classroom> $classrooms
     */
    private function seedClassroomAssignments(Collection $students, Collection $classrooms, AcademicYear $year): void
    {
        $classroomsById = $classrooms->keyBy(fn (Classroom $classroom): int => (int) $classroom->id);
        $assignedOn = CarbonImmutable::parse($year->starts_on);

        $students->each(function (Student $student) use ($classroomsById, $year, $assignedOn): void {
            $classroom = $classroomsById->get((int) $student->classroom_id);

            if ($classroom === null) {
                return;
            }

            ClassroomAssignment::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'classroom_id' => $classroom->id,
                    'academic_year_id' => $year->id,
                ],
                [
                    'grade_level' => $classroom->grade_level,
                    'assigned_on' => $assignedOn->toDateString(),
                    'notes' => 'Initial placement for ' . $year->name,
                ],
            );
        });
    }

    /**
     * @param Collection<string, Classroom> $classrooms
     * @param Collection<string, Staff> $staff
     * @param Collection<int, Subject> $subjects
     */
    private function seedClassroomStaff(Collection $classrooms, Collection $staff, Collection $subjects, AcademicYear $year): void
    {
        $subjectsByLevel = $subjects->groupBy(fn (Subject $subject): string => $subject->school_level->value);
        $teacherPool = $staff
            ->filter(fn (Staff $member): bool => $member->role === StaffRole::Teacher)
            ->values();
        $teacherCount = max($teacherPool->count(), 1);
        $teacherIndex = 0;

        $classrooms->each(function (Classroom $classroom) use ($staff, $subjectsByLevel, $year, $teacherPool, $teacherCount, &$teacherIndex): void {
            $levelSubjects = $subjectsByLevel->get($classroom->school_level->value);
            if ($levelSubjects === null || $levelSubjects->isEmpty()) {
                return;
            }

            $homeroom = $staff->firstWhere('id', $classroom->homeroom_staff_id)
                ?? $teacherPool->get($teacherIndex % $teacherCount);

            ClassroomStaff::updateOrCreate(
                [
                    'classroom_id' => $classroom->id,
                    'staff_id' => $homeroom?->id,
                    'academic_year_id' => $year->id,
                    'assignment_role' => AssignmentRole::Homeroom,
                ],
                [
                    'subject_id' => $levelSubjects->first()?->id,
                    'assigned_on' => CarbonImmutable::parse($year->starts_on)->toDateString(),
                    'notes' => 'Primary homeroom teacher',
                ],
            );

            $teacherIndex++;
            $subjectTeacher = $teacherPool->get($teacherIndex % $teacherCount);
            $subject = $levelSubjects->skip(1)->first() ?? $levelSubjects->first();

            ClassroomStaff::updateOrCreate(
                [
                    'classroom_id' => $classroom->id,
                    'staff_id' => $subjectTeacher?->id,
                    'academic_year_id' => $year->id,
                    'assignment_role' => AssignmentRole::SubjectTeacher,
                    'subject_id' => $subject?->id,
                ],
                [
                    'assigned_on' => CarbonImmutable::parse($year->starts_on)->addWeek()->toDateString(),
                    'notes' => 'Subject support teacher',
                ],
            );
        });
    }

    /**
     * @param Collection<string, Classroom> $classrooms
     * @param Collection<int, Subject> $subjects
     * @param Collection<string, Staff> $staff
     */
    private function seedSchedules(Collection $classrooms, Collection $subjects, Collection $staff, AcademicYear $year): void
    {
        $weekStart = CarbonImmutable::now()->startOfWeek();
        $subjectsByLevel = $subjects->groupBy(fn (Subject $subject): string => $subject->school_level->value);

        $classrooms->each(function (Classroom $classroom) use ($weekStart, $subjectsByLevel, $staff, $year): void {
            $levelSubjects = $subjectsByLevel->get($classroom->school_level->value);
            if ($levelSubjects === null || $levelSubjects->isEmpty()) {
                return;
            }

            $homeroom = $staff->firstWhere('id', $classroom->homeroom_staff_id);
            $periods = [
                ['day' => 0, 'start' => [7, 30], 'end' => [9, 0], 'color' => '#2563eb'],
                ['day' => 1, 'start' => [9, 15], 'end' => [11, 0], 'color' => '#22c55e'],
                ['day' => 3, 'start' => [7, 30], 'end' => [9, 30], 'color' => '#f97316'],
            ];

            foreach ($periods as $index => $period) {
                $subject = $levelSubjects->get($index % $levelSubjects->count());
                $start = $weekStart->addDays($period['day'])->setTime($period['start'][0], $period['start'][1]);
                $end = $weekStart->addDays($period['day'])->setTime($period['end'][0], $period['end'][1]);

                Schedule::updateOrCreate(
                    [
                        'title' => sprintf('%s - %s #%d', $classroom->name, $subject?->name ?? 'Session', $index + 1),
                        'classroom_id' => $classroom->id,
                        'starts_at' => $start,
                    ],
                    [
                        'subject_id' => $subject?->id,
                        'staff_id' => $homeroom?->id,
                        'academic_year_id' => $year->id,
                        'ends_at' => $end,
                        'is_all_day' => false,
                        'location' => 'Room ' . $classroom->code,
                        'description' => 'Default weekly session for ' . $classroom->name,
                        'color' => $period['color'],
                        'metadata' => $this->systemMetadata('schedule', ['index' => $index + 1]),
                    ],
                );
            }
        });
    }

    private function seedRecurringScheduleExample(AcademicYear $year, Collection $classrooms): void
    {
        $classroom = $classrooms->first();

        if ($classroom === null) {
            return;
        }

        $firstStart = CarbonImmutable::parse($year->starts_on)
            ->startOfWeek()
            ->addWeeks(2)
            ->setTime(7, 45);

        foreach (range(0, 3) as $week) {
            $start = $firstStart->addWeeks($week);
            $end = $start->addMinutes(45);

            Schedule::updateOrCreate(
                [
                    'title' => 'Weekly Flag Ceremony',
                    'starts_at' => $start,
                ],
                [
                    'classroom_id' => $classroom->id,
                    'academic_year_id' => $year->id,
                    'staff_id' => $classroom->homeroom_staff_id,
                    'ends_at' => $end,
                    'is_all_day' => false,
                    'location' => 'Main Yard',
                    'description' => 'Generated example of repeated schedule from seeder.',
                    'color' => '#f59e0b',
                    'metadata' => $this->systemMetadata('recurring_example', [
                        'week_offset' => $week,
                    ]),
                ],
            );
        }
    }

    /**
     * @return Collection<int, FeeTemplate>
     */
    private function seedFeeTemplates(): Collection
    {
        return collect(self::TUITION_RATES)
            ->map(fn (int $amount, string $grade): FeeTemplate => FeeTemplate::updateOrCreate(
                ['title' => sprintf('%s Tuition Template', GradeLevel::from($grade)->label())],
                [
                    'grade_level' => GradeLevel::from($grade),
                    'type' => FeeType::Tuition,
                    'amount' => $amount,
                    'currency' => 'IDR',
                    'due_in_days' => 14,
                    'is_active' => true,
                    'description' => 'System-protected tuition rate for ' . GradeLevel::from($grade)->label(),
                ],
            ))
            ->values();
    }

    /**
     * @param Collection<int, Student> $students
     * @param Collection<int, FeeTemplate> $templates
     */
    private function seedFees(Collection $students, AcademicYear $year, Collection $templates): void
    {
        $templatesByGrade = $templates->keyBy(fn (FeeTemplate $template): string => $template->grade_level?->value ?? '');
        $financeUserId = $this->financeUserId ?? User::query()->first()?->id;

        $students->each(function (Student $student) use ($year, $templatesByGrade, $financeUserId): void {
            $grade = $student->classroom?->grade_level ?? GradeLevel::Sd1;
            $amount = $this->resolveTuitionAmount($grade);
            $periods = [
                ['offset' => -1, 'status' => FeeStatus::Paid, 'payment' => PaymentStatus::Paid, 'ratio' => 1.0],
                ['offset' => 0, 'status' => FeeStatus::Partial, 'payment' => PaymentStatus::Partial, 'ratio' => 0.5],
                ['offset' => 1, 'status' => FeeStatus::Pending, 'payment' => PaymentStatus::Pending, 'ratio' => 0.0],
            ];

            foreach ($periods as $index => $config) {
                $dueDate = CarbonImmutable::now()->startOfMonth()->addMonths($config['offset']);
                $transaction = Transaction::updateOrCreate(
                    ['reference' => sprintf('TRX-%s-%s-%d', $student->student_number, $dueDate->format('Ym'), $index + 1)],
                    [
                        'label' => 'Tuition - ' . $dueDate->format('F Y'),
                        'type' => TransactionType::Income,
                        'category' => 'tuition',
                        'payment_status' => $config['payment'],
                        'payment_method' => 'transfer',
                        'amount' => $amount,
                        'currency' => 'IDR',
                        'due_date' => $dueDate->toDateString(),
                        'paid_at' => $config['ratio'] === 1.0 ? $dueDate->addDays(2) : null,
                        'academic_year_id' => $year->id,
                        'counterparty_name' => $student->full_name,
                        'recorded_by' => $financeUserId,
                        'notes' => 'System generated tuition entry',
                        'metadata' => $this->systemMetadata('tuition_transaction', ['grade_level' => $grade->value]),
                    ],
                );

                Fee::updateOrCreate(
                    ['reference' => sprintf('FEE-%s-%s-%d', $student->student_number, $dueDate->format('Ym'), $index + 1)],
                    [
                        'student_id' => $student->id,
                        'academic_year_id' => $year->id,
                        'transaction_id' => $transaction->id,
                        'title' => 'Tuition - ' . $dueDate->format('F Y'),
                        'type' => FeeType::Tuition,
                        'amount' => $amount,
                        'paid_amount' => $amount * $config['ratio'],
                        'currency' => 'IDR',
                        'due_date' => $dueDate->toDateString(),
                        'status' => $config['status'],
                        'paid_at' => $config['ratio'] === 1.0 ? $dueDate->addDays(2) : null,
                        'description' => 'Default tuition billing',
                        'metadata' => $this->systemMetadata('tuition_fee', [
                            'grade_level' => $grade->value,
                            'template_id' => $templatesByGrade->get($grade->value)?->id,
                        ]),
                    ],
                );
            }
        });
    }

    /**
     * @param Collection<int, Student> $students
     * @param Collection<string, Staff> $staff
     */
    private function seedAttendances(Collection $students, Collection $staff): void
    {
        $recorderId = $staff->first()?->user_id ?? User::query()->first()?->id;
        $days = $this->recentSchoolDays(5);

        $staff->each(function (Staff $member) use ($days, $recorderId): void {
            foreach ($days as $day) {
                StaffAttendance::updateOrCreate(
                    [
                        'staff_id' => $member->id,
                        'recorded_on' => $day->toDateString(),
                    ],
                    [
                        'status' => AttendanceStatus::Present,
                        'checked_in_at' => $day->setTime(7, 10),
                        'checked_out_at' => $day->setTime(15, 15),
                        'location' => 'Main Campus',
                        'recorded_by' => $recorderId,
                        'notes' => null,
                    ],
                );
            }
        });

        $students->each(function (Student $student, int $index) use ($days, $recorderId): void {
            foreach ($days as $dayIndex => $day) {
                $status = $dayIndex === 1 && $index % 7 === 0 ? AttendanceStatus::Late : AttendanceStatus::Present;

                StudentAttendance::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'recorded_on' => $day->toDateString(),
                    ],
                    [
                        'academic_year_id' => $student->academic_year_id,
                        'classroom_id' => $student->classroom_id,
                        'status' => $status,
                        'checked_in_at' => $day->setTime(7, $status === AttendanceStatus::Late ? 55 : 20),
                        'checked_out_at' => $day->setTime(14, 45),
                        'recorded_by' => $recorderId,
                        'notes' => $status === AttendanceStatus::Late ? 'Traffic delay reported by parent.' : null,
                    ],
                );
            }
        });
    }

    private function seedRegistrationPipeline(AcademicYear $targetYear, Collection $classrooms): void
    {
        $forms = [
            ['form_number' => 'REG-2025-001', 'student' => 'Nindi Putri', 'gender' => 'female', 'grade' => GradeLevel::TkA, 'status' => RegistrationStatus::Pending],
            ['form_number' => 'REG-2025-002', 'student' => 'Oscar Hartawan', 'gender' => 'male', 'grade' => GradeLevel::Sd1, 'status' => RegistrationStatus::PaymentVerified],
            ['form_number' => 'REG-2025-003', 'student' => 'Priska Aruna', 'gender' => 'female', 'grade' => GradeLevel::Sd4, 'status' => RegistrationStatus::Completed],
            ['form_number' => 'REG-2025-004', 'student' => 'Qori Naufal', 'gender' => 'male', 'grade' => GradeLevel::Sd2, 'status' => RegistrationStatus::PaymentVerified],
        ];

        foreach ($forms as $index => $form) {
            $classroom = $classrooms->get($form['grade']->value) ?? $classrooms->first();
            $intake = RegistrationIntake::updateOrCreate(
                ['form_number' => $form['form_number']],
                [
                    'payment_reference' => 'PAY-' . Str::padLeft((string) ($index + 1), 4, '0'),
                    'payment_method' => 'transfer',
                    'payment_amount' => 500_000,
                    'payment_received_at' => now()->toDateString(),
                    'guardian_name' => 'Prospective Parent #' . ($index + 1),
                    'guardian_phone' => '+62-813-77' . Str::padLeft((string) $index, 4, '0'),
                    'guardian_email' => 'prospect' . ($index + 1) . '@families.local',
                    'guardian_address' => 'Jl. Calon ' . ($index + 1),
                    'student_full_name' => $form['student'],
                    'student_date_of_birth' => now()->subYears(6 + $index)->toDateString(),
                    'student_gender' => $form['gender'],
                    'target_grade_level' => $form['grade'],
                    'academic_year_id' => $targetYear->id,
                    'classroom_id' => $classroom?->id,
                    'processed_by' => $this->financeUserId,
                    'processed_at' => now(),
                    'status' => $form['status'],
                    'notes' => 'Flow preview record for ' . $targetYear->name,
                ],
            );

            RegistrationIntakeDocument::updateOrCreate(
                ['registration_intake_id' => $intake->id, 'type' => 'birth_certificate'],
                [
                    'name' => 'Birth Certificate',
                    'file_path' => 'registration/' . Str::slug($intake->form_number) . '.pdf',
                    'notes' => 'System generated document placeholder.',
                ],
            );
        }
    }

    /**
     * @return Collection<int, CarbonImmutable>
     */
    private function recentSchoolDays(int $days): Collection
    {
        $date = CarbonImmutable::now()->startOfDay();
        $collection = collect();

        while ($collection->count() < $days) {
            if (! $date->isWeekend()) {
                $collection->push($date);
            }

            $date = $date->subDay();
        }

        return $collection->reverse()->values();
    }

    /**
     * @param array<int, SystemRole> $roles
     */
    private function ensureUser(string $name, string $email, array $roles): User
    {
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'status' => UserStatus::Active,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );

        $user->syncRoles(collect($roles)->map(fn (SystemRole $role): string => $role->value)->all());

        return $user;
    }

    private function buildStudentName(int $sequence): string
    {
        $first = self::STUDENT_FIRST_NAMES[$sequence % count(self::STUDENT_FIRST_NAMES)];
        $last = self::STUDENT_LAST_NAMES[$sequence % count(self::STUDENT_LAST_NAMES)];

        return $first . ' ' . $last;
    }

    private function resolveBirthDate(?GradeLevel $grade, int $sequence): CarbonImmutable
    {
        $age = match ($grade) {
            GradeLevel::Paud => 4,
            GradeLevel::TkA => 5,
            GradeLevel::TkB => 6,
            GradeLevel::Sd1 => 7,
            GradeLevel::Sd2 => 8,
            GradeLevel::Sd3 => 9,
            GradeLevel::Sd4 => 10,
            GradeLevel::Sd5 => 11,
            GradeLevel::Sd6 => 12,
            default => 10,
        };

        return CarbonImmutable::now()
            ->subYears($age)
            ->subMonths($sequence % 6);
    }

    private function resolveTuitionAmount(GradeLevel $grade): float
    {
        return self::TUITION_RATES[$grade->value] ?? 1_000_000;
    }

    /**
     * @param array<string, mixed> $extra
     * @return array<string, mixed>
     */
    private function systemMetadata(string $tag, array $extra = []): array
    {
        return array_merge([
            'seeded_by' => 'default_data_seeder',
            'locked_by' => SystemRole::SuperAdmin->value,
            'tag' => $tag,
        ], $extra);
    }

    /**
     * @param Collection<string, Staff> $staff
     */
    private function seedStaffDocuments(Collection $staff): void
    {
        $staff->each(function (Staff $member): void {
            StaffDocument::updateOrCreate(
                [
                    'staff_id' => $member->id,
                    'type' => 'contract',
                ],
                [
                    'name' => $member->staff_name . ' Contract',
                    'file_path' => 'staff-documents/' . Str::slug($member->staff_number) . '-contract.pdf',
                    'notes' => 'System protected contract placeholder.',
                ],
            );
        });
    }
}
