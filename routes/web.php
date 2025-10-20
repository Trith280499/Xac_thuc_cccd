<?php

// use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResetPassController;
use App\Http\Controllers\CccdController;


Route::get('/', function () {
    return view('home');
});
Route::get('/reset-form', function () {return view('reset');});

// Route::post('/reset', [ResetPassController::class, 'handleReset']);
Route::post('/form2 ', [ResetPassController::class, 'handleReset']);

Route::post('/cccd', [CccdController::class, 'process']);
