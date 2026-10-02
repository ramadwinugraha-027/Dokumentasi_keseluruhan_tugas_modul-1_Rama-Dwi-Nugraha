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
                'status' => 'Planned',
            ],
            [
                'code' => 'ACT-002',
                'title' => 'Seminar Web Quality & Testing',
                'description' => 'Pengenalan maintainability dan testing.',
                'activity_date' => '2026-10-12',
                'category_id' => $seminar?->id,
                'status' => 'Planned',
            ],
            [
                'code' => 'ACT-003',
                'title' => 'Praktikum Laravel 13 Relasi',
                'description' => 'Praktik implementasi Eloquent Relationship dan Clean Architecture.',
                'activity_date' => '2026-10-19',
                'category_id' => $praktikum?->id,
                'status' => 'Ongoing',
            ],
            [
                'code' => 'ACT-004',
                'title' => 'Presentasi Progress Proyek Web',
                'description' => 'Presentasi kemajuan proyek pemrograman web per kelompok.',
                'activity_date' => '2026-10-26',
                'category_id' => $project?->id,
                'status' => 'Done',
            ],
            [
                'code' => 'ACT-005',
                'title' => 'Evaluasi Akhir Modul 3',
                'description' => 'Evaluasi hasil pengerjaan modul.',
                'activity_date' => '2026-10-30',
                'category_id' => $evaluasi?->id,
                'status' => 'Done',
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
