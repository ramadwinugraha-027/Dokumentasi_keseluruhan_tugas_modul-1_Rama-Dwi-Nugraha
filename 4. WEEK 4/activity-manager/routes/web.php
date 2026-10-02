<?php

use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('activities.index');
});

// Resource CRUD Kegiatan (Activities)
Route::resource('activities', ActivityController::class);
