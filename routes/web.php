<?php
use App\Http\Controllers\PajakController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\BtsController;
use App\Http\Controllers\TransaksiBtsController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\TransaksiDomainController;
use App\Http\Controllers\JenisPembayaranController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReminderController;
use Illuminate\Support\Facades\Route;


Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('dashboard', DashboardController::class);
Route::resource('transaksi', TransaksiController::class);
Route::resource('motor', MotorController::class);
Route::resource('karyawan', KaryawanController::class);
Route::resource('bts', BtsController::class);
Route::resource('transaksi_bts', TransaksiBtsController::class);
Route::resource('domain', DomainController::class);
Route::resource('transaksi_domain', TransaksiDomainController::class);
Route::resource('jenis_pembayaran', JenisPembayaranController::class);
Route::resource('pembayaran', PembayaranController::class);
Route::resource('pajak', PajakController::class);
Route::resource('reminders', ReminderController::class);
Route::put('/reminders/{id}', [ReminderController::class, 'update'])->name('reminders.update');

Route::get('/download-excel', [PajakController::class, 'export_excel']);

Route::group(['prefix' => 'pajak'], function() {
    Route::get('/cache', [PajakController::class, 'cache'])->name('cache');
    Route::get('/import', [PajakController::class, 'import'])->name('import');
    Route::post('/import-proses', [PajakController::class, 'import_proses'])->name('import-proses');
});
