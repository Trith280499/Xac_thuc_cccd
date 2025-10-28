<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResetPassController;
use App\Http\Controllers\CccdAuthController;
use App\Http\Controllers\CccdVerifyController;
use App\Http\Controllers\LoaiTaiKhoanController;

use Illuminate\Http\Request;


Route::get('/', function () {
    return view('form1');
});

Route::post('/cccd-auth', [CccdAuthController::class, 'authenticate'])->name('cccd.auth');
Route::get('/logout', [CccdAuthController::class, 'logout'])->name('logout');
Route::post('/manual-approval', [CccdAuthController::class, 'manualApproval'])->name('manual.approval');

Route::get('/activated-cccds', [CccdVerifyController::class, 'getActivatedCccds']);
Route::get('/activated-cccds/paginated', [CccdVerifyController::class, 'getActivatedCccdsPaginated']);
Route::get('/activated-cccds/search', [CccdVerifyController::class, 'searchActivatedCccds']);
Route::get('/activated-cccds/{id}', [CccdVerifyController::class, 'getActivatedCccdDetail']);

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

// Route::prefix('api')->group(function() {
//     Route::post('/cccd-auth', [CccdAuthController::class, 'authenticate'])->name('api.cccd.auth');
//     Route::post('/reset-password', [ResetPassController::class, 'handleReset'])->name('api.reset.password');
//     Route::get('/student-accounts', [ResetPassController::class, 'getStudentAccounts'])->name('api.student.accounts');
// });

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
    Route::get('/getAllLoaiTK', [CccdVerifyController::class, 'getAllLoaiTK']);
});

//Form quản lý kích hoạt
Route::prefix('quan-ly-loai-tai-khoan')->group(function() {
    Route::get('/view', function () {
        return view('quan-ly-loai-tai-khoan');
    })->name('quan-ly-loai-tai-khoan.form');

    Route::get('/', function () {
        return view('quan-ly-loai-tai-khoan');
    })->name('quan-ly-loai-tai-khoan');
    
    // Route API để lấy dữ liệu
    Route::get('/api/danh-sach', [LoaiTaiKhoanController::class, 'index'])->name('quan-ly-loai-tai-khoan.api');
    Route::post('/api/them-moi', [LoaiTaiKhoanController::class, 'store'])->name('quan-ly-loai-tai-khoan.store');
    Route::put('/api/cap-nhat/{id}', [LoaiTaiKhoanController::class, 'update'])->name('quan-ly-loai-tai-khoan.update');
    Route::delete('/api/xoa/{id}', [LoaiTaiKhoanController::class, 'destroy'])->name('quan-ly-loai-tai-khoan.destroy');
    Route::get('/api/chi-tiet/{id}', [LoaiTaiKhoanController::class, 'show'])->name('quan-ly-loai-tai-khoan.show');
    
    // Route::get('/', [CccdVerifyController::class, 'getActivatedCccds'])->name('quan-ly-loai-tai-khoan');
});


Route::fallback(function () {
    return redirect('/');
});