<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\LogController; // ✅ TAMBAH INI

/*
|--------------------------------------------------------------------------
| DEFAULT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| DASHBOARD (ROLE BASED)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    if (!session()->has('role')) {
        return redirect('/login');
    }

    if (session('role') == 'owner') {
        return redirect('/owner/dashboard');
    }

    return view('dashboard');
});

/*
|--------------------------------------------------------------------------
| ADMIN AREA
|--------------------------------------------------------------------------
*/

Route::middleware('role:admin')->group(function () {

    // USER
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users/store', [UserController::class, 'store']);
    Route::post('/users/update/{id}', [UserController::class, 'update']);
    Route::get('/users/delete/{id}', [UserController::class, 'delete']);

    // AREA
    Route::get('/area', [AreaController::class, 'index']);
    Route::post('/area/store', [AreaController::class, 'store']);
    Route::post('/area/update/{id}', [AreaController::class, 'update']);
    Route::get('/area/delete/{id}', [AreaController::class, 'delete']);

    // TARIF
    Route::get('/tarif', [TarifController::class, 'index']);
    Route::post('/tarif/store', [TarifController::class, 'store']);
    Route::post('/tarif/update/{id}', [TarifController::class, 'update']);
    Route::get('/tarif/delete/{id}', [TarifController::class, 'delete']);

    // LOG ✅ PAKAI CONTROLLER, BUKAN CLOSURE
    Route::get('/log', [LogController::class, 'index']);

    // KENDARAAN
    Route::get('/kendaraan', [KendaraanController::class, 'index']);
    Route::post('/kendaraan/store', [KendaraanController::class, 'store']);
    Route::post('/kendaraan/update/{id}', [KendaraanController::class, 'update']);
    Route::get('/kendaraan/delete/{id}', [KendaraanController::class, 'delete']);

});

/*
|--------------------------------------------------------------------------
| PETUGAS + ADMIN (TRANSAKSI)
|--------------------------------------------------------------------------
*/

Route::middleware('role:petugas,admin')->group(function () {
    Route::get('/transaksi', [TransaksiController::class, 'index']);
    Route::post('/transaksi/masuk', [TransaksiController::class, 'masuk']);
    Route::get('/transaksi/keluar/{id}', [TransaksiController::class, 'keluar']);
    Route::get('/transaksi/cetak/{id}', [TransaksiController::class, 'cetak']);
});

/*
|--------------------------------------------------------------------------
| OWNER
|--------------------------------------------------------------------------
*/

Route::middleware('role:owner')->group(function () {

    Route::get('/owner/dashboard', [OwnerController::class, 'dashboard']);

    Route::get('/rekap', [RekapController::class, 'index']);
    Route::get('/rekap/cetak', [RekapController::class, 'cetak']);

    Route::get('/rekap/bulanan', [OwnerController::class, 'rekapBulanan']);
});