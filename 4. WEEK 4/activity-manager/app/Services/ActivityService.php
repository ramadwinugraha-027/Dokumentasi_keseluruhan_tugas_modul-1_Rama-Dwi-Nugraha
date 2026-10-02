<?php

namespace App\Services;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Collection;

class ActivityService
{
    /**
     * Mengambil semua kegiatan beserta data kategorinya.
     */
    public function getAll(): Collection
    {
        return Activity::with('category')
            ->orderBy('activity_date', 'asc')
            ->get();
    }

    /**
     * Membuat kegiatan baru.
     */
    public function create(array $data): Activity
    {
        return Activity::create($data);
    }

    /**
     * Memperbarui data kegiatan.
     */
    public function update(Activity $activity, array $data): Activity
    {
        $activity->update($data);

        return $activity->refresh();
    }

    /**
     * Menghapus kegiatan.
     */
    public function delete(Activity $activity): bool
    {
        return (bool) $activity->delete();
    }
}
