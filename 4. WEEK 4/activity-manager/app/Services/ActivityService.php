<?php

namespace App\Services;

use App\Models\Activity;
use DomainException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ActivityService
{
    public function getAll(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return Activity::with('category')
            ->filter($filters)
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data): Activity
    {
        $data['status'] = Activity::STATUS_DRAFT;

        return Activity::create($data);
    }

    public function update(Activity $activity, array $data): Activity
    {
        unset($data['status']);

        $activity->update($data);

        return $activity->refresh();
    }

    public function publish(Activity $activity): Activity
    {
        if ($activity->status !== Activity::STATUS_DRAFT) {
            throw new DomainException('Hanya kegiatan berstatus draft yang dapat dipublikasikan.');
        }

        if (! $activity->isComplete()) {
            throw new DomainException('Kegiatan tidak dapat dipublikasikan karena data belum lengkap (deskripsi wajib diisi).');
        }

        $activity->update(['status' => Activity::STATUS_PUBLISHED]);

        return $activity->refresh();
    }

    public function complete(Activity $activity): Activity
    {
        if ($activity->status !== Activity::STATUS_PUBLISHED) {
            throw new DomainException('Hanya kegiatan berstatus published yang dapat diselesaikan.');
        }

        $activity->update(['status' => Activity::STATUS_COMPLETED]);

        return $activity->refresh();
    }

    public function delete(Activity $activity): bool
    {
        return (bool) $activity->delete();
    }
}
