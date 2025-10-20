<?php

// use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResetPassController;

Route::get('/', function () {
    return view('reset');
});

Route::post('/reset', [ResetPassController::class, 'handleReset']);
