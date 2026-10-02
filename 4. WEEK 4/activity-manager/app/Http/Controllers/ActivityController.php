<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Models\Activity;
use App\Models\Category;
use App\Services\ActivityService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    protected ActivityService $activityService;

    public function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'category_id', 'status', 'sort']);
        $activities = $this->activityService->getAll($filters, 10);
        $categories = Category::orderBy('name')->get();
        $trashedCount = Activity::onlyTrashed()->count();

        return view('activities.index', compact('activities', 'categories', 'filters', 'trashedCount'));
    }

    public function trash(): View
    {
        $activities = $this->activityService->getTrash(10);

        return view('activities.trash', compact('activities'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('activities.create', compact('categories'));
    }

    public function store(StoreActivityRequest $request): RedirectResponse
    {
        $activity = $this->activityService->create($request->validated());

        return to_route('activities.show', $activity)
            ->with('success', 'Kegiatan berhasil dibuat dengan status draft.');
    }

    public function show(Activity $activity): View
    {
        $activity->load(['category', 'registrations']);

        return view('activities.show', compact('activity'));
    }

    public function edit(Activity $activity): View
    {
        $categories = Category::orderBy('name')->get();

        return view('activities.edit', compact('activity', 'categories'));
    }

    public function update(UpdateActivityRequest $request, Activity $activity): RedirectResponse
    {
        $this->activityService->update($activity, $request->validated());

        return to_route('activities.show', $activity)
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Activity $activity): RedirectResponse
    {
        $this->activityService->delete($activity);

        return to_route('activities.index')
            ->with('success', 'Kegiatan berhasil dipindahkan ke sampah (soft deleted).');
    }

    public function restore(int $id): RedirectResponse
    {
        $activity = $this->activityService->restore($id);

        return to_route('activities.trash')
            ->with('success', "Kegiatan '{$activity->title}' berhasil dipulihkan (restored).");
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $this->activityService->forceDelete($id);

        return to_route('activities.trash')
            ->with('success', 'Kegiatan berhasil dihapus secara permanen.');
    }

    public function publish(Activity $activity): RedirectResponse
    {
        try {
            $this->activityService->publish($activity);

            return back()->with('success', "Kegiatan '{$activity->title}' berhasil dipublikasikan.");
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function complete(Activity $activity): RedirectResponse
    {
        try {
            $this->activityService->complete($activity);

            return back()->with('success', "Kegiatan '{$activity->title}' telah diselesaikan.");
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
