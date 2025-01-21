<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\Invoice;
use App\Http\Controllers\InvoicePreview;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'index']);
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');
Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');

Route::middleware('auth')->group(function () {
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
});
