<?php

use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\Invoice;
use App\Http\Controllers\InvoicePreview;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'index']);
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post')->middleware('throttle:3,5');

Route::middleware('auth', 'activity.timeout')->group(function () {
    Route::get('dashboard', [AuthController::class, 'dashboard']);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('/invoice_setting', Invoice::class);
    Route::resource('/invoice_preview', InvoicePreview::class);
    Route::post('/export-preview', [InvoicePreview::class, 'export']);
    Route::resource('/device', DeviceController::class);
    Route::resource('/folder_device', FolderController::class);
    Route::get('/folders/{id}/devices', [DeviceController::class, 'getDevicesByFolder']);
    Route::post('/invoice_preview', [InvoicePreview::class, 'preview'])->name('invoice.preview');
    Route::post('/save-invoice', [InvoicePreview::class, 'saveInvoice']);
    Route::get('/download-invoice', [InvoicePreview::class, 'downloadInvoice']);
    Route::get('/invoice_setting', [Invoice::class, 'search'])->name('folders.search');
    Route::get('/folder', [FolderController::class, 'search'])->name('folder.search');
    Route::get('/devices', [DeviceController::class, 'search'])->name('device.search');
    Route::resource('/archive_invoice', ArchiveController::class);
    Route::get('/forgot-password', [AuthController::class, 'showVerifyEmailForm'])->name('verify-email');
    Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
    Route::post('/update-password', [AuthController::class, 'updatePassword'])->name('update-password');
});
