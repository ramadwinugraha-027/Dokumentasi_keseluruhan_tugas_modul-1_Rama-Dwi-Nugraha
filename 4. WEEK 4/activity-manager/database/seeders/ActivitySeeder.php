<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $workshop = Category::where('name', 'Workshop')->first();
        $seminar = Category::where('name', 'Seminar')->first();
        $praktikum = Category::where('name', 'Praktikum')->first();
        $project = Category::where('name', 'Project')->first();
        $evaluasi = Category::where('name', 'Evaluasi')->first();

        $activities = [
            [
                'code' => 'ACT-001',
                'title' => 'Workshop Git Dasar',
                'description' => 'Latihan kolaborasi repository dan branching workflow.',
                'activity_date' => '2026-10-05',
                'category_id' => $workshop?->id,
                'status' => 'draft',
            ],
            [
                'code' => 'ACT-002',
                'title' => 'Seminar Web Quality & Testing',
                'description' => 'Pengenalan maintainability dan testing.',
                'activity_date' => '2026-10-12',
                'category_id' => $seminar?->id,
                'status' => 'published',
            ],
            [
                'code' => 'ACT-003',
                'title' => 'Praktikum Laravel 13 Relasi',
                'description' => 'Praktik implementasi Eloquent Relationship dan Clean Architecture.',
                'activity_date' => '2026-10-19',
                'category_id' => $praktikum?->id,
                'status' => 'published',
            ],
            [
                'code' => 'ACT-004',
                'title' => 'Presentasi Progress Proyek Web',
                'description' => 'Presentasi kemajuan proyek pemrograman web per kelompok.',
                'activity_date' => '2026-10-26',
                'category_id' => $project?->id,
                'status' => 'completed',
            ],
            [
                'code' => 'ACT-005',
                'title' => 'Evaluasi Akhir Modul 3',
                'description' => 'Evaluasi hasil pengerjaan modul.',
                'activity_date' => '2026-10-30',
                'category_id' => $evaluasi?->id,
                'status' => 'completed',
            ],
            [
                'code' => 'ACT-006',
                'title' => 'Workshop UI/UX Design System',
                'description' => 'Membuat design token dan komponen UI figma.',
                'activity_date' => '2026-11-02',
                'category_id' => $workshop?->id,
                'status' => 'draft',
            ],
            [
                'code' => 'ACT-007',
                'title' => 'Seminar Cyber Security Awareness',
                'description' => 'Pentingnya pengamanan credential dan validasi input.',
                'activity_date' => '2026-11-05',
                'category_id' => $seminar?->id,
                'status' => 'draft',
            ],
            [
                'code' => 'ACT-008',
                'title' => 'Praktikum Database Indexing',
                'description' => 'Optimasi query dan indexing table besar.',
                'activity_date' => '2026-11-10',
                'category_id' => $praktikum?->id,
                'status' => 'published',
            ],
            [
                'code' => 'ACT-009',
                'title' => 'Review Sprint 1 Pengembangan Web',
                'description' => 'Review milestone pertama deliverable proyek.',
                'activity_date' => '2026-11-15',
                'category_id' => $project?->id,
                'status' => 'completed',
            ],
            [
                'code' => 'ACT-010',
                'title' => 'Workshop Continuous Integration',
                'description' => 'Setup automated testing pipeline di GitHub Actions.',
                'activity_date' => '2026-11-20',
                'category_id' => $workshop?->id,
                'status' => 'draft',
            ],
            [
                'code' => 'ACT-011',
                'title' => 'Seminar Cloud Deployment',
                'description' => 'Arsitektur containerization dan serverless.',
                'activity_date' => '2026-11-25',
                'category_id' => $seminar?->id,
                'status' => 'published',
            ],
            [
                'code' => 'ACT-012',
                'title' => 'Praktikum API Resource dan Auth',
                'description' => 'Membangun REST API dengan Sanctum auth token.',
                'activity_date' => '2026-12-01',
                'category_id' => $praktikum?->id,
                'status' => 'draft',
            ],
            [
                'code' => 'ACT-013',
                'title' => 'Draft Incomplete Activity',
                'description' => null,
                'activity_date' => '2026-12-05',
                'category_id' => $workshop?->id,
                'status' => 'draft',
            ],
        ];

        foreach ($activities as $data) {
            if ($data['category_id']) {
                Activity::firstOrCreate(
                    ['code' => $data['code']],
                    $data
                );
            }
        }
    }
}
