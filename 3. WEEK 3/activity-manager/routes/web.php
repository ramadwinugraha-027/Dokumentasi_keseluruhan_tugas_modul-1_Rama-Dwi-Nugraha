<?php

use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Route;

Route::get('/activities', [ActivityController::class, 'index'])
    ->name('activities.index');

Route::get('/activities/{activity}', [ActivityController::class, 'show'])
    ->name('activities.show');
