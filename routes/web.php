<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResetPassController;
use App\Http\Controllers\CccdAuthController;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('form1');
});

Route::post('/cccd-auth', [CccdAuthController::class, 'authenticate'])->name('cccd.auth');
Route::get('/logout', [CccdAuthController::class, 'logout'])->name('logout');
Route::post('/manual-approval', [CccdAuthController::class, 'manualApproval'])->name('manual.approval');

Route::prefix('form2')->group(function() {
    Route::get('/view', [CccdAuthController::class, 'showForm2'])->name('form2.view');
    
    Route::get('/student-accounts', [ResetPassController::class, 'getStudentAccounts'])->name('student.accounts');
    Route::post('/check-reset-status', [ResetPassController::class, 'checkResetStatus'])->name('check.reset.status');
    Route::post('/reset-password', [ResetPassController::class, 'handleReset'])->name('reset.password');
    Route::post('/bulk-reset-password', [ResetPassController::class, 'handleBulkReset'])->name('bulk.reset.password');
});

Route::prefix('form3')->group(function() {
    Route::get('/view', function () {
        return view('form3');
    })->name('form3.view');
});

Route::prefix('form4')->group(function() {
    Route::get('/view', function () {
        return view('form4');
    })->name('form4.view');
    
    Route::get('/lich-su-reset', [ResetPassController::class, 'getLichSuReset'])->name('lich.su.reset');
    Route::get('/lich-su-reset/{taiKhoanId}', [ResetPassController::class, 'getLichSuReset'])->name('lich.su.reset.detail');
});

Route::prefix('api')->group(function() {
    Route::post('/cccd-auth', [CccdAuthController::class, 'authenticate'])->name('api.cccd.auth');
    Route::post('/reset-password', [ResetPassController::class, 'handleReset'])->name('api.reset.password');
    Route::get('/student-accounts', [ResetPassController::class, 'getStudentAccounts'])->name('api.student.accounts');
});

Route::get('/cleanup-images', [CccdAuthController::class, 'cleanupOldImages'])->name('cleanup.images');

// Form xét duyệt
Route::get('/xet-duyet', function (Request $request) {
    return view('xet-duyet', [
        'cccd' => $request->get('cccd'),
        'image_url' => $request->get('image_url')
    ]);
})->name('xet-duyet.form');

// Xử lý gửi yêu cầu xét duyệt
Route::post('/submit-approval', [CccdAuthController::class, 'submitApproval'])->name('submit.approval');

Route::fallback(function () {
    return redirect('/');
});