<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\BodyMapController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// Update the dashboard route to use the controller
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Patient routes - accessible to both admin and medic
    Route::middleware(['can:view-patient'])->get('/patients', [\App\Http\Controllers\PatientController::class, 'index'])->name('patients.index');
    Route::middleware(['can:create-patient'])->get('/patients/create', [\App\Http\Controllers\PatientController::class, 'create'])->name('patients.create');
    Route::middleware(['can:create-patient'])->post('/patients', [\App\Http\Controllers\PatientController::class, 'store'])->name('patients.store');
    Route::middleware(['can:edit-patient'])->get('/patients/{id}/edit', [\App\Http\Controllers\PatientController::class, 'edit'])->name('patients.edit');
    Route::middleware(['can:edit-patient'])->put('/patients/{id}', [\App\Http\Controllers\PatientController::class, 'update'])->name('patients.update');
    Route::delete('/patients/{id}', [\App\Http\Controllers\PatientController::class, 'delete'])->name('patients.delete');
    Route::middleware(['can:view-patient'])->get('/patients/{id}', [\App\Http\Controllers\PatientController::class, 'show'])->name('patients.show');

    // Measure routes - accessible to both admin and medic
    Route::middleware(['can:view-measure'])->get('/measures', [\App\Http\Controllers\MeasureController::class, 'index'])->name('measures.index');
    Route::middleware(['can:create-measure'])->get('/measures/create', [\App\Http\Controllers\MeasureController::class, 'create'])->name('measures.create');
    Route::middleware(['can:create-measure'])->post('/measures', [\App\Http\Controllers\MeasureController::class, 'store'])->name('measures.store');
    Route::middleware(['can:edit-measure'])->get('/measures/{id}/edit', [\App\Http\Controllers\MeasureController::class, 'edit'])->name('measures.edit');
    Route::middleware(['can:edit-measure'])->put('/measures/{id}', [\App\Http\Controllers\MeasureController::class, 'update'])->name('measures.update');
    Route::middleware(['can:delete-measure'])->delete('/measures/{id}', [\App\Http\Controllers\MeasureController::class, 'delete'])->name('measures.delete');
    Route::middleware(['can:view-measure'])->get('/measures/{id}', [\App\Http\Controllers\MeasureController::class, 'show'])->name('measures.show');

    // User routes - accessible only to admin
    Route::middleware(['can:create-user'])->group(function () {
        Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [\App\Http\Controllers\UserController::class, 'create'])->name('users.create');
        Route::post('/users', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}/edit', [\App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [\App\Http\Controllers\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [\App\Http\Controllers\UserController::class, 'delete'])->name('users.delete');
        Route::get('/users/{id}', [\App\Http\Controllers\UserController::class, 'show'])->name('users.show');
    });

    // Body Map routes
    Route::get('/body-map', [BodyMapController::class, 'index'])->name('body-map.index');
    Route::get('/body-map/{patient}', [BodyMapController::class, 'show'])->name('body-map.show');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
