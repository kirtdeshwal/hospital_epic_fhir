<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/patients', [PatientController::class, 'getPatients']);
Route::get('/patients/epic-patient/{id}', [PatientController::class, 'getEpicPatient']);
Route::get('/patients/procedures/{id}', [PatientController::class, 'patientProcedures']);
Route::get('/patients/procedure/{id}', [PatientController::class, 'getProcedure']);
