<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'rama.dwi@polban.ac.id'],
            [
                'name' => 'Rama Dwi Nugraha',
                'password' => bcrypt('password123'),
            ]
        );

        $this->call([
            CategorySeeder::class,
            ActivitySeeder::class,
            RegistrationSeeder::class,
        ]);
    }
}
