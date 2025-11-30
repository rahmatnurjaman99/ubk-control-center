<?php

declare(strict_types=1);

return [
    'navigation' => [
        'access_control' => 'Access Control',
        'academics' => 'Academics',
        'people_staff' => 'Staff & HR',
        'people_students' => 'Students & Guardians',
        'admissions' => 'Admissions',
        'finance' => 'Finance',
        'attendance' => 'Attendance',
    ],

    'school_levels' => [
        'paud' => 'PAUD',
        'tk' => 'Kindergarten',
        'sd' => 'Elementary',
    ],

    'grade_levels' => [
        'paud' => 'Playgroup',
        'tka' => 'TK A',
        'tkb' => 'TK B',
        'sd_1' => 'Grade 1',
        'sd_2' => 'Grade 2',
        'sd_3' => 'Grade 3',
        'sd_4' => 'Grade 4',
        'sd_5' => 'Grade 5',
        'sd_6' => 'Grade 6',
    ],

    'users' => [
        'navigation' => [
            'label' => 'Users',
        ],
        'model' => [
            'singular' => 'User',
            'plural' => 'Users',
        ],
        'fields' => [
            'name' => 'Name',
            'email' => 'Email address',
            'email_verified_at' => 'Email verified at',
            'password' => 'Password',
            'password_confirmation' => 'Confirm password',
            'roles' => 'Roles',
            'avatar' => 'Avatar',
            'status' => 'Status',
        ],
        'table' => [
            'avatar' => 'Avatar',
            'verified_at' => 'Verified at',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
        ],
        'statuses' => [
            'active' => 'Active',
            'inactive' => 'Inactive',
        ],
    ],

    'roles' => [
        'navigation' => [
            'label' => 'Roles',
        ],
        'model' => [
            'singular' => 'Role',
            'plural' => 'Roles',
        ],
        'fields' => [
            'name' => 'Name',
            'guard_name' => 'Guard',
            'permissions' => 'Permissions',
        ],
        'table' => [
            'guard' => 'Guard',
            'permissions_count' => '# Permissions',
        ],
    ],

    'permissions' => [
        'navigation' => [
            'label' => 'Permissions',
        ],
        'model' => [
            'singular' => 'Permission',
            'plural' => 'Permissions',
        ],
        'fields' => [
            'name' => 'Name',
            'guard_name' => 'Guard',
            'roles' => 'Roles',
        ],
        'table' => [
            'guard' => 'Guard',
            'roles_count' => '# Roles',
        ],
    ],

    'academic_years' => [
        'navigation' => [
            'label' => 'Academic Years',
        ],
        'model' => [
            'singular' => 'Academic Year',
            'plural' => 'Academic Years',
        ],
        'fields' => [
            'code' => 'Code',
            'name' => 'Name',
            'starts_on' => 'Starts on',
            'ends_on' => 'Ends on',
            'is_current' => 'Current year',
            'code_helper' => 'Unique reference, e.g. 2024-2025.',
            'is_current_helper' => 'Used to highlight the active academic year.',
        ],
        'table' => [
            'code' => 'Code',
            'name' => 'Name',
            'starts_on' => 'Starts on',
            'ends_on' => 'Ends on',
            'current' => 'Current',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
        ],
        'filters' => [
            'current' => 'Current year',
            'date_range' => 'Date range',
            'starts_from' => 'Starts from :date',
            'ends_until' => 'Ends until :date',
            'starts_from_label' => 'Starts from',
            'ends_until_label' => 'Ends until',
        ],
    ],

    'staff' => [
        'navigation' => [
            'label' => 'Staff',
        ],
        'model' => [
            'singular' => 'Staff',
            'plural' => 'Staff',
        ],
        'fields' => [
            'user' => 'User',
            'staff_number' => 'Staff ID',
            'staff_name' => 'Staff name',
            'role' => 'Role',
            'joined_on' => 'Joined on',
            'phone' => 'Phone',
            'emergency_contact_name' => 'Emergency contact name',
            'emergency_contact_phone' => 'Emergency contact phone',
            'education_level' => 'Highest education',
            'education_institution' => 'Institution',
            'graduated_year' => 'Graduated year',
            'documents' => 'Documents',
            'document_name' => 'Document name',
            'document_type' => 'Document type',
            'document_file' => 'File',
            'document_notes' => 'Notes',
        ],
        'table' => [
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
            'deleted_at' => 'Deleted at',
        ],
        'filters' => [
            'role' => 'Role',
            'joined_period' => 'Joined period',
            'joined_from' => 'Joined from',
            'joined_until' => 'Joined until',
            'joined_from_indicator' => 'Joined from :date',
            'joined_until_indicator' => 'Joined until :date',
        ],
        'sections' => [
            'profile' => 'Profile',
            'emergency' => 'Emergency contact',
            'education' => 'Education',
            'metadata' => 'Metadata',
            'documents' => 'Documents',
        ],
        'roles' => [
            'principal' => 'Principal',
            'vice_principal' => 'Vice Principal',
            'administrator' => 'Administrator',
            'teacher' => 'Teacher',
            'counselor' => 'Counselor',
            'accountant' => 'Accountant',
        ],
        'education_levels' => [
            'high_school' => 'High School',
            'diploma' => 'Diploma',
            'bachelor' => 'Bachelor',
            'master' => 'Master',
            'doctorate' => 'Doctorate',
            'other' => 'Other',
        ],
    ],

    'guardians' => [
        'navigation' => [
            'label' => 'Guardians',
        ],
        'model' => [
            'singular' => 'Guardian',
            'plural' => 'Guardians',
        ],
        'fields' => [
            'user' => 'User',
            'guardian_number' => 'Guardian ID',
            'full_name' => 'Full name',
            'relationship' => 'Relationship',
            'phone' => 'Phone',
            'email' => 'Email',
            'occupation' => 'Occupation',
            'address' => 'Address',
            'legacy_reference' => 'Legacy reference',
        ],
        'table' => [
            'guardian_number' => 'Guardian ID',
            'full_name' => 'Full name',
            'relationship' => 'Relationship',
            'phone' => 'Phone',
            'email' => 'Email',
            'students_count' => 'Students',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
        ],
        'filters' => [
            'trashed' => 'Deleted records',
        ],
    ],

    'classrooms' => [
        'navigation' => [
            'label' => 'Classrooms',
        ],
        'model' => [
            'singular' => 'Classroom',
            'plural' => 'Classrooms',
        ],
        'fields' => [
            'code' => 'Class code',
            'name' => 'Class name',
            'school_level' => 'School level',
            'grade_level' => 'Grade',
            'academic_year' => 'Academic year',
            'capacity' => 'Capacity',
            'homeroom_staff' => 'Homeroom teacher',
            'description' => 'Description',
        ],
        'table' => [
            'code' => 'Code',
            'name' => 'Name',
            'school_level' => 'School level',
            'grade_level' => 'Grade',
            'academic_year' => 'Academic year',
            'capacity' => 'Capacity',
            'students_count' => 'Students',
            'updated_at' => 'Updated at',
        ],
        'filters' => [
            'school_level' => 'School level',
            'grade_level' => 'Grade',
            'academic_year' => 'Academic year',
            'trashed' => 'Deleted records',
        ],
    ],

    'subjects' => [
        'navigation' => [
            'label' => 'Subjects',
        ],
        'model' => [
            'singular' => 'Subject',
            'plural' => 'Subjects',
        ],
        'fields' => [
            'code' => 'Subject code',
            'name' => 'Subject name',
            'category' => 'Category',
            'category_name' => 'Category name',
            'category_slug' => 'Category slug',
            'category_description' => 'Category description',
            'school_level' => 'School level',
            'academic_year' => 'Academic year',
            'is_compulsory' => 'Compulsory',
            'credit_hours' => 'Credit hours',
            'description' => 'Description',
            'classrooms' => 'Classrooms',
        ],
        'table' => [
            'code' => 'Code',
            'name' => 'Name',
            'category' => 'Category',
            'school_level' => 'School level',
            'academic_year' => 'Academic year',
            'classrooms' => 'Classrooms',
            'is_compulsory' => 'Compulsory',
            'credit_hours' => 'Credit hours',
            'updated_at' => 'Updated at',
        ],
        'filters' => [
            'school_level' => 'School level',
            'academic_year' => 'Academic year',
            'category' => 'Category',
            'trashed' => 'Deleted records',
        ],
    ],

    'subject_categories' => [
        'navigation' => [
            'label' => 'Subject Categories',
        ],
        'model' => [
            'singular' => 'Subject Category',
            'plural' => 'Subject Categories',
        ],
        'fields' => [
            'name' => 'Category name',
            'slug' => 'Slug',
            'description' => 'Description',
        ],
        'table' => [
            'name' => 'Category',
            'slug' => 'Slug',
            'slug_copied' => 'Slug copied!',
            'subjects_count' => '# Subjects',
            'updated_at' => 'Updated at',
        ],
    ],

    'classroom_assignments' => [
        'fields' => [
            'student' => 'Student',
            'academic_year' => 'Academic year',
            'classroom' => 'Classroom',
            'grade_level' => 'Grade',
            'assigned_on' => 'Assigned on',
            'removed_on' => 'Removed on',
            'notes' => 'Notes',
        ],
        'table' => [
            'student' => 'Student',
            'academic_year' => 'Academic year',
            'classroom' => 'Classroom',
            'school_level' => 'School level',
            'grade_level' => 'Grade',
            'assigned_on' => 'Assigned on',
            'removed_on' => 'Removed on',
            'notes' => 'Notes',
        ],
        'filters' => [
            'academic_year' => 'Academic year',
            'student' => 'Student',
            'trashed' => 'Deleted records',
        ],
    ],

    'classroom_staff' => [
        'fields' => [
            'staff' => 'Staff member',
            'academic_year' => 'Academic year',
            'role' => 'Role',
            'subject' => 'Subject',
            'assigned_on' => 'Assigned on',
            'removed_on' => 'Removed on',
            'notes' => 'Notes',
        ],
        'table' => [
            'staff' => 'Staff member',
            'role' => 'Role',
            'subject' => 'Subject',
            'academic_year' => 'Academic year',
            'assigned_on' => 'Assigned on',
            'removed_on' => 'Removed on',
            'notes' => 'Notes',
        ],
        'filters' => [
            'role' => 'Role',
            'academic_year' => 'Academic year',
            'staff' => 'Staff',
            'trashed' => 'Deleted records',
        ],
    ],

    'assignment_roles' => [
        'homeroom' => 'Homeroom teacher',
        'subject_teacher' => 'Subject teacher',
    ],

    'roles' => [
        'super_admin' => 'Super Admin',
        'admin' => 'Administrator',
        'teacher' => 'Teacher',
        'guardian' => 'Parent / Guardian',
        'student' => 'Student',
        'panel_user' => 'Panel User',
    ],

    'transactions' => [
        'navigation' => [
            'label' => 'Transactions',
        ],
        'model' => [
            'singular' => 'Transaction',
            'plural' => 'Transactions',
        ],
        'fields' => [
            'reference' => 'Reference',
            'label' => 'Title',
            'type' => 'Type',
            'category' => 'Category',
            'amount' => 'Amount',
            'currency' => 'Currency',
            'payment_status' => 'Payment status',
            'payment_method' => 'Payment method',
            'due_date' => 'Due date',
            'paid_at' => 'Paid at',
            'academic_year' => 'Academic year',
            'counterparty_name' => 'Counterparty',
            'notes' => 'Notes',
            'source' => 'Linked record',
            'recorded_by' => 'Recorded by',
        ],
        'table' => [
            'reference' => 'Reference',
            'label' => 'Title',
            'type' => 'Type',
            'category' => 'Category',
            'amount' => 'Amount',
            'payment_status' => 'Payment',
            'paid_at' => 'Paid at',
            'recorded_by' => 'Recorded by',
            'updated_at' => 'Updated at',
        ],
        'filters' => [
            'type' => 'Type',
            'payment_status' => 'Payment status',
            'academic_year' => 'Academic year',
            'trashed' => 'Deleted records',
        ],
    ],

    'fees' => [
        'navigation' => [
            'label' => 'Student Fees',
        ],
        'model' => [
            'singular' => 'Fee',
            'plural' => 'Fees',
        ],
        'fields' => [
            'reference' => 'Reference',
            'title' => 'Title',
            'type' => 'Fee type',
            'amount' => 'Amount',
            'currency' => 'Currency',
            'due_date' => 'Due date',
            'status' => 'Status',
            'paid_at' => 'Paid at',
            'student' => 'Student',
            'academic_year' => 'Academic year',
            'transaction' => 'Transaction',
            'description' => 'Description',
        ],
        'table' => [
            'reference' => 'Reference',
            'title' => 'Title',
            'student' => 'Student',
            'type' => 'Type',
            'amount' => 'Amount',
            'status' => 'Status',
            'due_date' => 'Due date',
            'paid_at' => 'Paid at',
        ],
        'filters' => [
            'type' => 'Type',
            'status' => 'Status',
            'academic_year' => 'Academic year',
            'trashed' => 'Deleted records',
        ],
        'types' => [
            'tuition' => 'Tuition',
            'registration' => 'Registration',
            'uniform' => 'Uniform',
            'misc' => 'Miscellaneous',
        ],
        'statuses' => [
            'pending' => 'Pending',
            'partial' => 'Partially paid',
            'paid' => 'Paid',
            'cancelled' => 'Cancelled',
        ],
        'promotion' => [
            'title' => 'Tuition fee for :grade (:year)',
            'description' => 'Automatically generated after promotion for :year.',
        ],
    ],

    'fee_templates' => [
        'navigation' => [
            'label' => 'Grade Fee Templates',
        ],
        'model' => [
            'singular' => 'Fee template',
            'plural' => 'Fee templates',
        ],
        'sections' => [
            'details' => 'Fee details',
        ],
        'fields' => [
            'title' => 'Title',
            'grade_level' => 'Grade level',
            'type' => 'Fee type',
            'amount' => 'Amount',
            'currency' => 'Currency',
            'due_in_days' => 'Due in (days)',
            'is_active' => 'Active',
            'description' => 'Description',
        ],
        'table' => [
            'title' => 'Title',
            'grade_level' => 'Grade',
            'type' => 'Type',
            'amount' => 'Amount',
            'due_in_days' => 'Due',
            'days' => 'days',
            'is_active' => 'Active',
        ],
        'filters' => [
            'grade_level' => 'Grade level',
            'type' => 'Type',
            'is_active' => 'Status',
            'trashed' => 'Deleted records',
        ],
    ],

    'transaction_types' => [
        'income' => 'Income',
        'expense' => 'Expense',
    ],

    'payment_statuses' => [
        'pending' => 'Pending',
        'partial' => 'Partially paid',
        'paid' => 'Paid',
        'cancelled' => 'Cancelled',
    ],

    'attendance' => [
        'statuses' => [
            'present' => 'Present',
            'absent' => 'Absent',
            'late' => 'Late',
            'excused' => 'Excused',
            'sick' => 'Sick',
        ],
    ],

    'student_attendances' => [
        'navigation' => [
            'label' => 'Student Attendance',
        ],
        'model' => [
            'singular' => 'Student attendance',
            'plural' => 'Student attendance',
        ],
        'sections' => [
            'details' => 'Attendance details',
            'notes' => 'Notes',
        ],
        'fields' => [
            'student' => 'Student',
            'academic_year' => 'Academic year',
            'classroom' => 'Classroom',
            'recorded_on' => 'Date',
            'status' => 'Status',
            'checked_in_at' => 'Check-in time',
            'checked_out_at' => 'Check-out time',
            'notes' => 'Notes',
        ],
        'table' => [
            'recorded_on' => 'Date',
            'student' => 'Student',
            'classroom' => 'Classroom',
            'status' => 'Status',
            'checked_in_at' => 'In',
            'checked_out_at' => 'Out',
        ],
        'filters' => [
            'status' => 'Status',
            'student' => 'Student',
            'classroom' => 'Classroom',
            'recorded_period' => 'Date range',
            'from' => 'From',
            'until' => 'Until',
        ],
        'actions' => [
            'generate' => 'Generate daily roster',
            'generate_success' => ':count students prepared for attendance.',
        ],
    ],

    'staff_attendances' => [
        'navigation' => [
            'label' => 'Staff Attendance',
        ],
        'model' => [
            'singular' => 'Staff attendance',
            'plural' => 'Staff attendance',
        ],
        'sections' => [
            'details' => 'Attendance details',
            'notes' => 'Notes',
        ],
        'fields' => [
            'staff' => 'Staff',
            'recorded_on' => 'Date',
            'status' => 'Status',
            'checked_in_at' => 'Check-in time',
            'checked_out_at' => 'Check-out time',
            'location' => 'Location',
            'notes' => 'Notes',
        ],
        'table' => [
            'recorded_on' => 'Date',
            'staff' => 'Staff',
            'status' => 'Status',
            'location' => 'Location',
            'checked_in_at' => 'In',
            'checked_out_at' => 'Out',
        ],
        'filters' => [
            'status' => 'Status',
            'staff' => 'Staff',
            'recorded_period' => 'Date range',
            'from' => 'From',
            'until' => 'Until',
        ],
        'actions' => [
            'generate' => 'Generate daily roster',
            'generate_success' => ':count staff prepared for attendance.',
        ],
    ],

    'registration_intakes' => [
        'navigation' => [
            'label' => 'Registration Intake',
        ],
        'model' => [
            'singular' => 'Registration Intake',
            'plural' => 'Registration Intakes',
        ],
        'sections' => [
            'payment' => 'Payment details',
            'guardian' => 'Parent / Guardian',
            'student' => 'Student information',
            'processing' => 'Processing notes',
        ],
        'fields' => [
            'form_number' => 'Form number',
            'payment_reference' => 'Payment reference',
            'payment_method' => 'Payment method',
            'payment_amount' => 'Amount paid',
            'payment_received_at' => 'Payment date',
            'guardian_name' => 'Guardian name',
            'guardian_phone' => 'Guardian phone',
            'guardian_email' => 'Guardian email',
            'guardian_address' => 'Guardian address',
            'student_full_name' => 'Student full name',
            'student_date_of_birth' => 'Student date of birth',
            'student_gender' => 'Student gender',
            'target_grade_level' => 'Target grade level',
            'academic_year' => 'Academic year',
            'classroom' => 'Preferred classroom',
            'status' => 'Status',
            'notes' => 'Internal notes',
            'processed_at' => 'Processed at',
            'processed_by' => 'Processed by',
            'documents' => 'Supporting documents',
            'document_name' => 'Document name',
            'document_type' => 'Document type',
            'document_file' => 'File',
            'document_notes' => 'Document notes',
        ],
        'table' => [
            'form_number' => 'Form',
            'guardian' => 'Guardian',
            'student' => 'Student',
            'status' => 'Status',
            'payment_amount' => 'Amount',
            'payment_received_at' => 'Paid on',
            'created_at' => 'Created',
        ],
        'filters' => [
            'status' => 'Status',
            'academic_year' => 'Academic year',
            'trashed' => 'Deleted records',
        ],
        'statuses' => [
            'pending' => 'Awaiting verification',
            'payment_verified' => 'Payment verified',
            'completed' => 'Converted to student',
            'cancelled' => 'Cancelled',
        ],
        'actions' => [
            'convert' => 'Convert to student',
            'convert_success' => 'Student record created successfully.',
            'convert_failed' => 'Conversion failed',
            'assignment_note' => 'Auto-created from registration intake :form',
        ],
        'validation' => [
            'classroom_full' => 'Classroom :classroom is already at full capacity.',
        ],
    ],

    'students' => [
        'navigation' => [
            'label' => 'Students',
        ],
        'model' => [
            'singular' => 'Student',
            'plural' => 'Students',
        ],
        'fields' => [
            'student_number' => 'Student ID',
            'full_name' => 'Full name',
            'date_of_birth' => 'Date of birth',
            'gender' => 'Gender',
            'status' => 'Status',
            'enrolled_on' => 'Enrolled on',
            'legacy_reference' => 'Legacy reference',
            'guardian' => 'Guardian',
            'academic_year' => 'Academic year',
            'classroom' => 'Classroom',
        ],
        'statuses' => [
            'active' => 'Active',
            'graduated' => 'Graduated',
            'transferred' => 'Transferred',
            'inactive' => 'Inactive',
        ],
        'actions' => [
            'promote' => 'Promote / Graduate',
            'target_academic_year' => 'Destination academic year',
            'target_grade_level' => 'Next grade (optional)',
            'target_classroom' => 'Specific classroom (optional)',
            'success_promoted' => 'Student promoted to :grade',
            'success_graduated' => 'Student has graduated.',
            'promotion_fees_created' => 'Fees created: :fees.',
            'eligibility_status' => 'Eligibility status',
            'eligibility_ready' => 'Eligible for promotion (all fees paid).',
            'eligibility_pending_fees' => 'Pending fees detected. Approval required before promoting.',
            'eligibility_pending_scores' => 'Awaiting academic eligibility data.',
            'outstanding_fees' => 'Outstanding fees',
            'outstanding_fees_message' => ':count outstanding fees totaling :amount. Confirm before promoting.',
            'override_outstanding_fees' => 'I confirm promotion despite outstanding fees.',
            'outstanding_fees_confirmation_required' => 'Approval is required to continue with outstanding fees.',
        ],
    ],
];
