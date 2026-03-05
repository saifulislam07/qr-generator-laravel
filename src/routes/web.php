<?php

use Illuminate\Support\Facades\Route;
use Saifulislam07\QrGenerator\Http\Controllers\QRController;

Route::group(['prefix' => 'qr-generator', 'middleware' => ['web']], function () {
    Route::get('/', [QRController::class, 'index'])->name('qr.index');
    Route::post('/generate', [QRController::class, 'generate'])->name('qr.generate');
    Route::get('/download/{fileName}', [QRController::class, 'download'])->name('qr.download');
});
