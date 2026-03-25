<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ReportController;

Route::post('/generate-report-json', [ReportController::class, 'generateJson']);
Route::post('/generate-pdf', [ReportController::class, 'generatePdf']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
