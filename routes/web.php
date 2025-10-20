<?php

// use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResetPassController;

Route::get('/', function () {
    return view('form2');
});

Route::post('/form2 ', [ResetPassController::class, 'handleReset']);

