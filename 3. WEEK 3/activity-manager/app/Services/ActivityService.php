<?php

namespace App\Services;

use App\Models\Activity;
use DomainException;

class ActivityService
{
    private const TRANSITIONS = [
        'Planned' => ['Planned', 'Ongoing'],
        'Ongoing' => ['Ongoing', 'Done'],
        'Done'    => ['Done'], 
    ];

    public function update(Activity $activity, array $data): Activity
    {
        $nextStatus = $data['status'] ?? $activity->status;
        
        // Pengecekan aturan bisnis di sisi server
        $this->ensureValidTransition($activity->status, $nextStatus);

        $activity->update($data);
        return $activity->refresh();
    }

    private function ensureValidTransition(string $current, string $next): void
    {
        $allowed = self::TRANSITIONS[$current] ?? [];
        if (! in_array($next, $allowed, true)) {
            // Jika ada yang melakukan inspect element / bypass UI, exception ini akan meledak di server
            throw new DomainException("Status tidak boleh mundur.");
        }
    }

    public function create(array $data): Activity
    {
        return Activity::create($data);
    }
}