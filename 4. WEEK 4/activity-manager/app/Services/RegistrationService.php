<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Registration;
use DomainException;
use Illuminate\Support\Facades\DB;

class RegistrationService
{
    public function register(Activity $activity, array $data, bool $simulateFail = false): Registration
    {
        if ($activity->status !== Activity::STATUS_PUBLISHED) {
            throw new DomainException('Pendaftaran hanya dapat dilakukan untuk kegiatan yang telah dipublikasikan (published).');
        }

        if ($activity->activity_date < now()->startOfDay()) {
            throw new DomainException('Pendaftaran ditolak karena tanggal kegiatan sudah lewat.');
        }

        if ($activity->registered_count >= $activity->capacity) {
            throw new DomainException('Pendaftaran ditolak karena kuota pendaftaran sudah penuh.');
        }

        if (Registration::where('activity_id', $activity->id)->where('email', $data['email'])->exists()) {
            throw new DomainException('Email ini sudah terdaftar pada kegiatan yang sama.');
        }

        return DB::transaction(function () use ($activity, $data, $simulateFail) {
            $registration = Registration::create([
                'activity_id' => $activity->id,
                'participant_name' => $data['participant_name'],
                'email' => $data['email'],
                'registered_at' => now(),
                'participant_phone' => $data['participant_phone'] ?? null,
                'status' => 'Registered',
            ]);

            $activity->increment('registered_count');

            if ($simulateFail) {
                throw new \RuntimeException('Simulasi kegagalan update registered_count untuk menguji rollback atomis.');
            }

            return $registration;
        });
    }
}
