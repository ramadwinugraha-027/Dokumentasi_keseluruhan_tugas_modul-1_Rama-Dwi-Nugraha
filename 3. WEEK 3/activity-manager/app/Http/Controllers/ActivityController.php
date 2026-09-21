<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(): View
    {
        $activities = Activity::query()
            ->orderBy('activity_date')
            ->get();

        return view('activities.index', compact('activities'));
    }

    public function show(Activity $activity): View
    {
        return view('activities.show', compact('activity'));
    }
}