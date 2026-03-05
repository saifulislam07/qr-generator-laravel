<?php

use App\Http\Controllers\QRController;
use Illuminate\Support\Facades\Route;

Route::get('/', [QRController::class, 'index']);
Route::post('/generate', [QRController::class, 'generate'])->name('qr.generate');
Route::get('/download/{fileName}', [QRController::class, 'download'])->name('qr.download');
