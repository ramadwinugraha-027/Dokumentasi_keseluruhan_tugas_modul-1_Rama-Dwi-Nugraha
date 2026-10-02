<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('activities.index');
});

Route::get('activities/trash', [ActivityController::class, 'trash'])->name('activities.trash');
Route::patch('activities/{id}/restore', [ActivityController::class, 'restore'])->name('activities.restore');
Route::delete('activities/{id}/force-delete', [ActivityController::class, 'forceDelete'])->name('activities.force-delete');
Route::patch('activities/{activity}/publish', [ActivityController::class, 'publish'])->name('activities.publish');
Route::patch('activities/{activity}/complete', [ActivityController::class, 'complete'])->name('activities.complete');
Route::post('activities/{activity}/registrations', [RegistrationController::class, 'store'])->name('activities.registrations.store');
Route::resource('activities', ActivityController::class);
