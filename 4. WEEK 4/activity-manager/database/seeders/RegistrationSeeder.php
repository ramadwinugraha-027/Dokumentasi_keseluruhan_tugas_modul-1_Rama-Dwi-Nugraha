<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Registration;
use Illuminate\Database\Seeder;

class RegistrationSeeder extends Seeder
{
    public function run(): void
    {
        $activities = Activity::all();

        foreach ($activities as $activity) {
            Registration::firstOrCreate(
                [
                    'activity_id' => $activity->id,
                    'email' => 'rama.dwi@polban.ac.id',
                ],
                [
                    'participant_name' => 'Rama Dwi Nugraha',
                    'participant_phone' => '081234567890',
                    'registered_at' => now(),
                    'status' => 'Confirmed',
                ]
            );

            Registration::firstOrCreate(
                [
                    'activity_id' => $activity->id,
                    'email' => 'mahasiswa.dummy@polban.ac.id',
                ],
                [
                    'participant_name' => 'Mahasiswa Contoh',
                    'participant_phone' => '089876543210',
                    'registered_at' => now(),
                    'status' => 'Registered',
                ]
            );

            $activity->update([
                'registered_count' => $activity->registrations()->count(),
            ]);
        }
    }
}
