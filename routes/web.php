<?php

// use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResetPassController;
use App\Http\Controllers\CccdController;


Route::get('/', function () {
    return view('reset');
});
Route::get('/reset-form', function () {return view('reset');});

// Route::post('/reset', [ResetPassController::class, 'handleReset']);
// Route::get('/', function () {
//     return view('form2');
// });

Route::post('/student-info', [ResetPassController::class, 'getInfo']);
Route::post('/reset-password', [ResetPassController::class, 'handleReset']);

Route::prefix('form2')
->group(function() {
    Route::get('/view', function () {return view('form2');});
    Route::get('/getInfo ', [ResetPassController::class, 'getInfo']);
    Route::post('/form2 ', [ResetPassController::class, 'handleReset']);

    // Route::post('/form2 ', [ResetPassController::class, 'handleReset']);
});
Route::prefix('form3')
->group(function() {
    Route::get('/view', function () {return view('form3');});
});
Route::prefix('form4')
->group(function() {
    Route::get('/view', function () {return view('form4');});
});

Route::post('/cccd', [CccdController::class, 'process']);