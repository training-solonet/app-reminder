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

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [HomeController::class, 'home']);
	Route::get('dashboard', function () {
		return view('dashboard');
	})->name('dashboard');

	Route::get('billing', function () {
		return view('billing');
	})->name('billing');

	Route::get('profile', function () {
		return view('profile');
	})->name('profile');

	Route::get('rtl', function () {
		return view('rtl');
	})->name('rtl');

	Route::get('user-management', function () {
		return view('laravel-examples/user-management');
	})->name('user-management');

	Route::get('tables', function () {
		return view('tables');
	})->name('tables');

    Route::get('virtual-reality', function () {
		return view('virtual-reality');
	})->name('virtual-reality');

    Route::get('static-sign-in', function () {
		return view('static-sign-in');
	})->name('sign-in');

    Route::get('static-sign-up', function () {
		return view('static-sign-up');
	})->name('sign-up');

    Route::get('/logout', [SessionsController::class, 'destroy']);
	Route::get('/user-profile', [InfoUserController::class, 'create']);
	Route::post('/user-profile', [InfoUserController::class, 'store']);
    Route::get('/login', function () {
		return view('dashboard');
	})->name('sign-up');
});



Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [SessionsController::class, 'create']);
    Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');

});

Route::get('/login', function () {
    return view('session/login-session');
})->name('login');



Route::resource('transaksi', TransaksiController::class);
Route::resource('motor', MotorController::class);
Route::resource('karyawan', KaryawanController::class);
Route::resource('bts', BtsController::class);
Route::resource('transaksi_bts', TransaksiBtsController::class);
Route::resource('domain', DomainController::class);
Route::resource('transaksi_domain', TransaksiDomainController::class);
Route::resource('jenis_pembayaran', JenisPembayaranController::class);
Route::resource('pembayaran', PembayaranController::class);
Route::resource('pajak',PajakController::class);
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('reminders', ReminderController::class);
Route::put('/reminders/{id}', [ReminderController::class, 'update'])->name('reminders.update');

Route::group(['prefix' => 'pajak'], function(){
    Route::get('/cache',[PajakController::class,'cache'])->name('cache');
    Route::get('/import',[PajakController::class,'import'])->name('import');
    Route::post('/import-proses',[PajakController::class,'import_proses'])->name('import-proses');

});


