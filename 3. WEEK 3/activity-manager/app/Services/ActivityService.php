<?php

namespace App\Services;

use App\Models\Activity;
use DomainException;

class ActivityService
{
    private const ALLOWED_TRANSITIONS = [
        'Planned' => ['Planned', 'Ongoing'],
        'Ongoing' => ['Ongoing', 'Done'],
        'Done' => ['Done'],
    ];

    public function create(array $data): Activity
    {
        return Activity::create($data);
    }

    public function update(Activity $activity, array $data): Activity
    {
        $nextStatus = $data['status'] ?? $activity->status;

        $this->ensureValidTransition($activity->status, $nextStatus);

        $activity->update($data);

        return $activity->refresh();
    }

    private function ensureValidTransition(string $currentStatus, string $nextStatus): void
    {
        $allowedNextStatuses = self::ALLOWED_TRANSITIONS[$currentStatus] ?? [];

        if (! in_array($nextStatus, $allowedNextStatuses, true)) {
            throw new DomainException("Status tidak boleh mundur dari {$currentStatus} ke {$nextStatus}.");
        }
    }
}
