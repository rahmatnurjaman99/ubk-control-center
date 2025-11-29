<?php

declare(strict_types=1);

return [
    'navigation' => [
        'access_control' => 'Kontrol Akses',
        'academics' => 'Akademik',
        'people_staff' => 'Kepegawaian',
        'people_students' => 'Kesiswaan',
        'admissions' => 'Pendaftaran',
    ],

    'school_levels' => [
        'paud' => 'PAUD',
        'tk' => 'Taman Kanak-kanak',
        'sd' => 'Sekolah Dasar',
    ],

    'grade_levels' => [
        'paud' => 'Kelompok Bermain',
        'tka' => 'TK A',
        'tkb' => 'TK B',
        'sd_1' => 'Kelas 1',
        'sd_2' => 'Kelas 2',
        'sd_3' => 'Kelas 3',
        'sd_4' => 'Kelas 4',
        'sd_5' => 'Kelas 5',
        'sd_6' => 'Kelas 6',
    ],

    'users' => [
        'navigation' => [
            'label' => 'Pengguna',
        ],
        'model' => [
            'singular' => 'Pengguna',
            'plural' => 'Pengguna',
        ],
        'fields' => [
            'name' => 'Nama',
            'email' => 'Alamat email',
            'email_verified_at' => 'Email diverifikasi pada',
            'password' => 'Kata sandi',
            'password_confirmation' => 'Konfirmasi kata sandi',
            'roles' => 'Peran',
            'avatar' => 'Avatar',
            'status' => 'Status',
        ],
        'table' => [
            'avatar' => 'Avatar',
            'verified_at' => 'Diverifikasi pada',
            'created_at' => 'Dibuat pada',
            'updated_at' => 'Diperbarui pada',
        ],
        'statuses' => [
            'active' => 'Aktif',
            'inactive' => 'Tidak aktif',
        ],
    ],

    'roles' => [
        'navigation' => [
            'label' => 'Peran',
        ],
        'model' => [
            'singular' => 'Peran',
            'plural' => 'Peran',
        ],
        'fields' => [
            'name' => 'Nama',
            'guard_name' => 'Penjaga',
            'permissions' => 'Izin',
        ],
        'table' => [
            'guard' => 'Penjaga',
            'permissions_count' => 'Jumlah izin',
        ],
    ],

    'permissions' => [
        'navigation' => [
            'label' => 'Izin',
        ],
        'model' => [
            'singular' => 'Izin',
            'plural' => 'Izin',
        ],
        'fields' => [
            'name' => 'Nama',
            'guard_name' => 'Penjaga',
            'roles' => 'Peran',
        ],
        'table' => [
            'guard' => 'Penjaga',
            'roles_count' => 'Jumlah peran',
        ],
    ],

    'academic_years' => [
        'navigation' => [
            'label' => 'Tahun Ajaran',
        ],
        'model' => [
            'singular' => 'Tahun Ajaran',
            'plural' => 'Tahun Ajaran',
        ],
        'fields' => [
            'code' => 'Kode',
            'name' => 'Nama',
            'starts_on' => 'Mulai pada',
            'ends_on' => 'Berakhir pada',
            'is_current' => 'Tahun aktif',
            'code_helper' => 'Referensi unik, contoh 2024-2025.',
            'is_current_helper' => 'Digunakan untuk menandai tahun ajaran aktif.',
        ],
        'table' => [
            'code' => 'Kode',
            'name' => 'Nama',
            'starts_on' => 'Mulai pada',
            'ends_on' => 'Berakhir pada',
            'current' => 'Aktif',
            'created_at' => 'Dibuat pada',
            'updated_at' => 'Diperbarui pada',
        ],
        'filters' => [
            'current' => 'Tahun aktif',
            'date_range' => 'Rentang tanggal',
            'starts_from' => 'Mulai :date',
            'ends_until' => 'Berakhir :date',
            'starts_from_label' => 'Mulai dari',
            'ends_until_label' => 'Sampai dengan',
        ],
    ],

    'staff' => [
        'navigation' => [
            'label' => 'Staf',
        ],
        'model' => [
            'singular' => 'Staf',
            'plural' => 'Staf',
        ],
        'fields' => [
            'user' => 'Pengguna',
            'staff_number' => 'Nomor staf',
            'staff_name' => 'Nama staf',
            'role' => 'Peran',
            'joined_on' => 'Bergabung pada',
            'phone' => 'Telepon',
            'emergency_contact_name' => 'Nama kontak darurat',
            'emergency_contact_phone' => 'Telepon kontak darurat',
            'education_level' => 'Pendidikan terakhir',
            'education_institution' => 'Institusi',
            'graduated_year' => 'Tahun kelulusan',
            'documents' => 'Dokumen',
            'document_name' => 'Nama dokumen',
            'document_type' => 'Jenis dokumen',
            'document_file' => 'Berkas',
            'document_notes' => 'Catatan',
        ],
        'table' => [
            'created_at' => 'Dibuat pada',
            'updated_at' => 'Diperbarui pada',
            'deleted_at' => 'Dihapus pada',
        ],
        'filters' => [
            'role' => 'Peran',
            'joined_period' => 'Periode bergabung',
            'joined_from' => 'Mulai bergabung',
            'joined_until' => 'Sampai',
            'joined_from_indicator' => 'Bergabung sejak :date',
            'joined_until_indicator' => 'Bergabung hingga :date',
        ],
        'sections' => [
            'profile' => 'Profil',
            'emergency' => 'Kontak darurat',
            'education' => 'Pendidikan',
            'metadata' => 'Metadata',
            'documents' => 'Dokumen',
        ],
        'roles' => [
            'principal' => 'Kepala Sekolah',
            'vice_principal' => 'Wakil Kepala Sekolah',
            'administrator' => 'Administrator',
            'teacher' => 'Guru',
            'counselor' => 'Konselor',
            'accountant' => 'Akuntan',
        ],
        'education_levels' => [
            'high_school' => 'SMA/SMK sederajat',
            'diploma' => 'Diploma',
            'bachelor' => 'Sarjana',
            'master' => 'Magister',
            'doctorate' => 'Doktor',
            'other' => 'Lainnya',
        ],
    ],

    'guardians' => [
        'navigation' => [
            'label' => 'Wali Murid',
        ],
        'model' => [
            'singular' => 'Wali',
            'plural' => 'Wali',
        ],
        'fields' => [
            'user' => 'Pengguna',
            'guardian_number' => 'ID wali',
            'full_name' => 'Nama lengkap',
            'relationship' => 'Hubungan',
            'phone' => 'Telepon',
            'email' => 'Email',
            'occupation' => 'Pekerjaan',
            'address' => 'Alamat',
            'legacy_reference' => 'Referensi legacy',
        ],
        'table' => [
            'guardian_number' => 'ID wali',
            'full_name' => 'Nama lengkap',
            'relationship' => 'Hubungan',
            'phone' => 'Telepon',
            'email' => 'Email',
            'students_count' => 'Jumlah siswa',
            'created_at' => 'Dibuat pada',
            'updated_at' => 'Diperbarui pada',
        ],
        'filters' => [
            'trashed' => 'Data terhapus',
        ],
    ],

    'classrooms' => [
        'navigation' => [
            'label' => 'Kelas',
        ],
        'model' => [
            'singular' => 'Kelas',
            'plural' => 'Kelas',
        ],
        'fields' => [
            'code' => 'Kode kelas',
            'name' => 'Nama kelas',
            'school_level' => 'Jenjang',
            'grade_level' => 'Tingkat',
            'academic_year' => 'Tahun ajaran',
            'capacity' => 'Kapasitas',
            'homeroom_staff' => 'Wali kelas',
            'description' => 'Deskripsi',
        ],
        'table' => [
            'code' => 'Kode',
            'name' => 'Nama',
            'school_level' => 'Jenjang',
            'grade_level' => 'Tingkat',
            'academic_year' => 'Tahun ajaran',
            'capacity' => 'Kapasitas',
            'students_count' => 'Jumlah siswa',
            'updated_at' => 'Diperbarui pada',
        ],
        'filters' => [
            'school_level' => 'Jenjang',
            'grade_level' => 'Tingkat',
            'academic_year' => 'Tahun ajaran',
            'trashed' => 'Data terhapus',
        ],
    ],

    'subjects' => [
        'navigation' => [
            'label' => 'Mata Pelajaran',
        ],
        'model' => [
            'singular' => 'Mata Pelajaran',
            'plural' => 'Mata Pelajaran',
        ],
        'fields' => [
            'code' => 'Kode mapel',
            'name' => 'Nama mapel',
            'category' => 'Kategori',
            'category_name' => 'Nama kategori',
            'category_slug' => 'Slug kategori',
            'category_description' => 'Deskripsi kategori',
            'school_level' => 'Jenjang',
            'academic_year' => 'Tahun ajaran',
            'is_compulsory' => 'Wajib',
            'credit_hours' => 'Jam pelajaran',
            'description' => 'Deskripsi',
            'classrooms' => 'Kelas',
        ],
        'table' => [
            'code' => 'Kode',
            'name' => 'Nama',
            'category' => 'Kategori',
            'school_level' => 'Jenjang',
            'academic_year' => 'Tahun ajaran',
            'classrooms' => 'Kelas',
            'is_compulsory' => 'Wajib',
            'credit_hours' => 'Jam pelajaran',
            'updated_at' => 'Diperbarui pada',
        ],
        'filters' => [
            'school_level' => 'Jenjang',
            'academic_year' => 'Tahun ajaran',
            'category' => 'Kategori',
            'trashed' => 'Data terhapus',
        ],
    ],

    'subject_categories' => [
        'navigation' => [
            'label' => 'Kategori Mapel',
        ],
        'model' => [
            'singular' => 'Kategori Mapel',
            'plural' => 'Kategori Mapel',
        ],
        'fields' => [
            'name' => 'Nama kategori',
            'slug' => 'Slug',
            'description' => 'Deskripsi',
        ],
        'table' => [
            'name' => 'Kategori',
            'slug' => 'Slug',
            'slug_copied' => 'Slug disalin!',
            'subjects_count' => 'Jumlah mapel',
            'updated_at' => 'Diperbarui pada',
        ],
    ],

    'classroom_assignments' => [
        'fields' => [
            'student' => 'Siswa',
            'academic_year' => 'Tahun ajaran',
            'classroom' => 'Kelas',
            'grade_level' => 'Tingkat',
            'assigned_on' => 'Mulai bergabung',
            'removed_on' => 'Berakhir',
            'notes' => 'Catatan',
        ],
        'table' => [
            'student' => 'Siswa',
            'academic_year' => 'Tahun ajaran',
            'classroom' => 'Kelas',
            'school_level' => 'Jenjang',
            'grade_level' => 'Tingkat',
            'assigned_on' => 'Mulai bergabung',
            'removed_on' => 'Berakhir',
            'notes' => 'Catatan',
        ],
        'filters' => [
            'academic_year' => 'Tahun ajaran',
            'student' => 'Siswa',
            'trashed' => 'Data terhapus',
        ],
    ],

    'classroom_staff' => [
        'fields' => [
            'staff' => 'Pegawai',
            'academic_year' => 'Tahun ajaran',
            'role' => 'Peran',
            'subject' => 'Mata pelajaran',
            'assigned_on' => 'Mulai ditugaskan',
            'removed_on' => 'Berakhir',
            'notes' => 'Catatan',
        ],
        'table' => [
            'staff' => 'Pegawai',
            'role' => 'Peran',
            'subject' => 'Mata pelajaran',
            'academic_year' => 'Tahun ajaran',
            'assigned_on' => 'Mulai ditugaskan',
            'removed_on' => 'Berakhir',
            'notes' => 'Catatan',
        ],
        'filters' => [
            'role' => 'Peran',
            'academic_year' => 'Tahun ajaran',
            'staff' => 'Pegawai',
            'trashed' => 'Data terhapus',
        ],
    ],

    'assignment_roles' => [
        'homeroom' => 'Wali kelas',
        'subject_teacher' => 'Guru mata pelajaran',
    ],

    'roles' => [
        'super_admin' => 'Super Admin',
        'admin' => 'Administrator',
        'teacher' => 'Guru',
        'guardian' => 'Wali / Orang tua',
        'student' => 'Siswa',
        'panel_user' => 'Pengguna panel',
    ],

    'registration_intakes' => [
        'navigation' => [
            'label' => 'Pendaftaran',
        ],
        'model' => [
            'singular' => 'Pendaftaran',
            'plural' => 'Data Pendaftaran',
        ],
        'sections' => [
            'payment' => 'Data pembayaran',
            'guardian' => 'Orang tua / Wali',
            'student' => 'Informasi siswa',
            'processing' => 'Catatan proses',
        ],
        'fields' => [
            'form_number' => 'Nomor formulir',
            'payment_reference' => 'Referensi pembayaran',
            'payment_method' => 'Metode pembayaran',
            'payment_amount' => 'Jumlah dibayar',
            'payment_received_at' => 'Tanggal bayar',
            'guardian_name' => 'Nama wali',
            'guardian_phone' => 'Telepon wali',
            'guardian_email' => 'Email wali',
            'guardian_address' => 'Alamat wali',
            'student_full_name' => 'Nama lengkap siswa',
            'student_date_of_birth' => 'Tanggal lahir siswa',
            'student_gender' => 'Jenis kelamin siswa',
            'target_grade_level' => 'Tingkat tujuan',
            'academic_year' => 'Tahun ajaran',
            'classroom' => 'Kelas pilihan',
            'status' => 'Status',
            'notes' => 'Catatan internal',
            'processed_at' => 'Diproses pada',
            'processed_by' => 'Diproses oleh',
            'documents' => 'Dokumen pendukung',
            'document_name' => 'Nama dokumen',
            'document_type' => 'Jenis dokumen',
            'document_file' => 'Berkas',
            'document_notes' => 'Catatan dokumen',
        ],
        'table' => [
            'form_number' => 'Formulir',
            'guardian' => 'Wali',
            'student' => 'Siswa',
            'status' => 'Status',
            'payment_amount' => 'Jumlah',
            'payment_received_at' => 'Tanggal bayar',
            'created_at' => 'Dibuat',
        ],
        'filters' => [
            'status' => 'Status',
            'academic_year' => 'Tahun ajaran',
            'trashed' => 'Data terhapus',
        ],
        'statuses' => [
            'pending' => 'Menunggu verifikasi',
            'payment_verified' => 'Pembayaran terverifikasi',
            'completed' => 'Sudah jadi siswa',
            'cancelled' => 'Dibatalkan',
        ],
        'actions' => [
            'convert' => 'Jadikan siswa',
            'convert_success' => 'Data siswa berhasil dibuat.',
            'convert_failed' => 'Gagal memproses pendaftaran',
            'assignment_note' => 'Otomatis dari formulir pendaftaran :form',
        ],
        'validation' => [
            'classroom_full' => 'Kelas :classroom sudah penuh.',
        ],
    ],

    'students' => [
        'navigation' => [
            'label' => 'Siswa',
        ],
        'model' => [
            'singular' => 'Siswa',
            'plural' => 'Siswa',
        ],
        'fields' => [
            'student_number' => 'NIS',
            'full_name' => 'Nama lengkap',
            'date_of_birth' => 'Tanggal lahir',
            'gender' => 'Jenis kelamin',
            'status' => 'Status',
            'enrolled_on' => 'Tanggal masuk',
            'legacy_reference' => 'Referensi legacy',
            'guardian' => 'Wali',
            'academic_year' => 'Tahun ajaran',
            'classroom' => 'Kelas',
        ],
        'statuses' => [
            'active' => 'Aktif',
            'graduated' => 'Lulus',
            'transferred' => 'Pindah',
            'inactive' => 'Tidak aktif',
        ],
        'actions' => [
            'promote' => 'Naik Kelas / Lulus',
            'target_academic_year' => 'Tahun ajaran tujuan',
            'target_grade_level' => 'Tingkat berikutnya (opsional)',
            'target_classroom' => 'Kelas tujuan (opsional)',
            'success_promoted' => 'Siswa naik ke :grade',
            'success_graduated' => 'Siswa dinyatakan lulus.',
        ],
    ],
];
