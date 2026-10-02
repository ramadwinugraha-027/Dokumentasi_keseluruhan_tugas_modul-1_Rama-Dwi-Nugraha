<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Workshop', 'description' => 'Kegiatan pelatihan interaktif dan praktik langsung.'],
            ['name' => 'Seminar', 'description' => 'Pemaparan materi oleh narasumber ahli di bidangnya.'],
            ['name' => 'Praktikum', 'description' => 'Sesi praktikum laboratorium dan pengerjaan modul.'],
            ['name' => 'Project', 'description' => 'Pengerjaan tugas besar atau proyek kolaboratif.'],
            ['name' => 'Evaluasi', 'description' => 'Sesi review, kuis, atau evaluasi capaian pembelajaran.'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
