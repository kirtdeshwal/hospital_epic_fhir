<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [PatientController::class, 'epic_oauth']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::post('/patients/upload-patients', [PatientController::class, 'upload_patients'])->name('patients.upload');
    Route::get('/patients/get-epic-access-token', [PatientController::class, 'create_epic_oauth']);
    Route::get('/patients/epic-patient/{id}', [PatientController::class, 'getEpicPatient'])->name('patients.epic-patient');
    Route::get('/patients/procedures/{id}', [PatientController::class, 'patientProcedures'])->name('patients.procedures');
    Route::get('/patients/procedure/{id}', [PatientController::class, 'getProcedure'])->name('patients.procedure');
});

Route::get('/patients/epic-oauth', [PatientController::class, 'epic_oauth']);


require __DIR__.'/auth.php';
