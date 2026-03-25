<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
