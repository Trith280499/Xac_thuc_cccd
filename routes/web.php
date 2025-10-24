<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResetPassController;
use App\Http\Controllers\CccdAuthController;
use App\Http\Controllers\CccdVerifyController;
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
    Route::get('/reset-history', [ResetPassController::class, 'getResetHistory']);
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
Route::prefix('xet-duyet')->group(function() {
    Route::get('/view', function (Request $request) {
        return view('xet-duyet', [
            'cccd' => $request->get('cccd'),
            'image_url' => $request->get('image_url')
        ]);
    })->name('xet-duyet.form');
    Route::post('/check-cccd-status', [CccdVerifyController::class, 'checkCccdStatus']);
    Route::post('/submit-approval', [CccdVerifyController::class, 'submitApproval'])->name('submit.approval');
});

//Form quản lý xét duyệt
Route::prefix('quan-ly-xet-duyet')->group(function() {
    Route::get('/view', function () {
        return view('quan-ly-xet-duyet');
    })->name('quan-ly-xet-duyet.form');
    Route::get('/', [CccdVerifyController::class, 'getAllApprovals']);
    Route::post('/', [CccdVerifyController::class, 'updateApprovalStatus'])->name('update.approval.status');
});

// Xử lý gửi yêu cầu xét duyệt

Route::fallback(function () {
    return redirect('/');
});