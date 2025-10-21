<?php

// use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResetPassController;
use App\Http\Controllers\CccdController;
use App\Http\Controllers\CccdAuthController;

Route::get('/', function () {
    return view('form1');
});
 // return view('upload');
// });
// Route::get('/upload-form', function () {return view('upload');});
Route::get('/form1', function () {
    return view('form1');
});

// Route::get('/reset-form', function () {return view('reset');});

//Route::post('/reset', [ResetPassController::class, 'handleReset']);
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

//api cho check up ảnh từ điện thoại hay up ảnh trong ngày
// Route::post('/cccd', [CccdController::class, 'process'])->name('cccd.process');

//giả lập api xác thực cccd
// Route::post('/cccd-auth', [CccdAuthController::class, 'authenticate'])->name('cccd.auth');

//api gọi để kiểm tra tồn tại sinh viên theo cccd
Route::post('/cccd-auth', [CccdAuthController::class, 'authenticate'])->name('cccd.auth');
// Route::match(['get', 'post'], '/check-info', [CccdAuthController::class, 'checkInfo'])->name('cccd.check');
