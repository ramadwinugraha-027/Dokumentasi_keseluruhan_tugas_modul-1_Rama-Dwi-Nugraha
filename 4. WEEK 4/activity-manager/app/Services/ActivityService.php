<?php

namespace App\Services;

use App\Models\Activity;
use DomainException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ActivityService
{
    public function getAll(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return Activity::with('category')
            ->filter($filters)
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getTrash(int $perPage = 10): LengthAwarePaginator
    {
        return Activity::onlyTrashed()
            ->with('category')
            ->latest('deleted_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data): Activity
    {
        $data['status'] = Activity::STATUS_DRAFT;

        if (isset($data['poster']) && $data['poster'] instanceof UploadedFile) {
            $data['poster_path'] = $data['poster']->store('posters', 'public');
        }
        unset($data['poster']);

        return Activity::create($data);
    }

    public function update(Activity $activity, array $data): Activity
    {
        unset($data['status']);

        if (isset($data['poster']) && $data['poster'] instanceof UploadedFile) {
            $newPath = $data['poster']->store('posters', 'public');

            if ($activity->poster_path && Storage::disk('public')->exists($activity->poster_path)) {
                Storage::disk('public')->delete($activity->poster_path);
            }

            $data['poster_path'] = $newPath;
        }
        unset($data['poster']);

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

    public function restore(int $id): Activity
    {
        $activity = Activity::onlyTrashed()->findOrFail($id);
        $activity->restore();

        return $activity;
    }

    public function forceDelete(int $id): bool
    {
        $activity = Activity::onlyTrashed()->findOrFail($id);

        if ($activity->poster_path && Storage::disk('public')->exists($activity->poster_path)) {
            Storage::disk('public')->delete($activity->poster_path);
        }

        return (bool) $activity->forceDelete();
    }
}
