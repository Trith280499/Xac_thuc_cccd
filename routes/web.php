<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResetPassController;
use App\Http\Controllers\CccdController;
use App\Http\Controllers\CccdAuthController;
use App\Http\Controllers\VerifyAccountController;
use Illuminate\Support\Facades\DB;

Route::post('/upload-cccd', [CccdController::class, 'upload'])->name('cccd.upload');
Route::post('/cccd-auth', [CccdAuthController::class, 'authenticate'])->name('cccd.auth');
Route::get('/logout', [CccdAuthController::class, 'logout'])->name('logout');

Route::get('/verify-account', [VerifyAccountController::class, 'showForm']);
Route::post('/verify-account', [VerifyAccountController::class, 'verify']);
Route::get('/not-student', [VerifyAccountController::class, 'notStudent']);

Route::get('/', function () {
    return view('form1');
});

Route::prefix('form2')->group(function() {
    Route::get('/view', [CccdAuthController::class, 'showForm2'])->name('form2.view');
    Route::post('/check-reset-status', [ResetPassController::class, 'checkResetStatus']);
    Route::post('/reset-password', [ResetPassController::class, 'handleReset']);
});
Route::prefix('form3')
->group(function() {
    Route::get('/view', function () {return view('form3');});
});
Route::prefix('form4')
->group(function() {
    Route::get('/view', function () {return view('form4');});
    Route::get('/lich-su-reset', function () {
    $history = DB::table('lich_su_reset')
                ->orderBy('thoi_gian_reset', 'desc')
                ->get();
    
    return response()->json($history);
});
});

//api cho check up ảnh từ điện thoại hay up ảnh trong ngày
// Route::post('/cccd', [CccdController::class, 'process'])->name('cccd.process');

//giả lập api xác thực cccd
// Route::post('/cccd-auth', [CccdAuthController::class, 'authenticate'])->name('cccd.auth');

