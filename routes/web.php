<?php

use App\Http\Controllers\authcontroller;
use App\Http\Controllers\barangcontroller;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\kategoricontroller;
use App\Http\Controllers\levelcontroller;
use App\Http\Controllers\profilecontroller;
use App\Http\Controllers\registercontroller;
use App\Http\Controllers\stokcontroller;
use App\Http\Controllers\suppliercontroller;
use App\Http\Controllers\transaksicontroller;
use App\Http\Controllers\usercontroller;
use App\Http\Controllers\welcomeController;
use App\Models\transaksimodel;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::pattern('id', '[0-9]+');
Route::get('login', [authcontroller::class, 'login'])->name('login');
Route::post('login', [authcontroller::class, 'postlogin']);
Route::get('register', [registercontroller::class, 'register']);
Route::post('register', [registercontroller::class, 'store']);
Route::get('logout', [authcontroller::class, 'logout'])->middleware('auth');

Route::middleware(['auth'])->group(function () {

    Route::get('/', [welcomeController::class, 'index']);
    //route user
    Route::group(['prefix' => 'user', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [usercontroller::class, 'index']); //menampilkan halaman awal user
        Route::post('/list', [usercontroller::class, 'list']); //menampilkan data user dalam bentuk json untuk data tables
        Route::get('/create', [usercontroller::class, 'create']); //menampilkan halaman form tambah user
        Route::post('/', [usercontroller::class, 'store']); //menyimpan data user baru
        Route::get('/create_ajax', [usercontroller::class, 'create_ajax']); //menampilkan halaman form tambah user ajax
        Route::post('/ajax', [usercontroller::class, 'store_ajax']); //meyimpan data user baru ajax
        Route::get('/{id}', [usercontroller::class, 'show']); //menampilkan detail user
        Route::get('/{id}/edit', [usercontroller::class, 'edit']); //menampilkan halaman form edit
        Route::put('/{id}', [usercontroller::class, 'update']); //meyimpan perubahan data user
        Route::get('/{id}/edit_ajax', [usercontroller::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [usercontroller::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [usercontroller::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [usercontroller::class, 'delete_ajax']);
        Route::get('/{id}/show_ajax', [usercontroller::class, 'show_ajax']);
        Route::delete('/{id}', [usercontroller::class, 'destroy']); //menghapus data user
        Route::get('/import', [usercontroller::class, 'import']);
        Route::post('/import_ajax', [usercontroller::class, 'import_ajax']);
        Route::get('/export_excel', [usercontroller::class, 'export_excel']);
        Route::get('/export_pdf', [usercontroller::class, 'export_pdf']);
    });

    //route level

    Route::group(['prefix' => 'level', 'middleware' => 'authorize:ADM'], function () {
        Route::get('/', [levelcontroller::class, 'index']);
        Route::post('/list', [levelcontroller::class, 'list']);
        Route::get('/create', [levelcontroller::class, 'create']);
        Route::post('/', [levelcontroller::class, 'store']);
        Route::get('/create_ajax', [levelcontroller::class, 'create_ajax']); //menampilkan halaman form tambah user ajax
        Route::post('/ajax', [levelcontroller::class, 'store_ajax']); //meyimpan data user baru ajax
        Route::get('/{id}', [levelcontroller::class, 'show']);
        Route::get('/{id}/edit', [levelcontroller::class, 'edit']);
        Route::put('/{id}', [levelcontroller::class, 'update']);
        Route::get('/{id}/edit_ajax', [levelcontroller::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [levelcontroller::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [levelcontroller::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [levelcontroller::class, 'delete_ajax']);
        Route::get('/{id}/show_ajax', [levelcontroller::class, 'show_ajax']);
        Route::delete('/{id}', [levelcontroller::class, 'destroy']);
        Route::get('/import', [levelcontroller::class, 'import']);
        Route::post('/import_ajax', [levelcontroller::class, 'import_ajax']);
        Route::get('/export_excel', [levelcontroller::class, 'export_excel']);
        Route::get('/export_pdf', [levelcontroller::class, 'export_pdf']);
    });

    //route kategori
    Route::group(['prefix' => 'kategori', 'middleware' => 'authorize:ADM,MNG,STF'], function () {
        Route::get('/', [kategoricontroller::class, 'index']);
        Route::post('/list', [kategoricontroller::class, 'list']);
        Route::get('/create', [kategoricontroller::class, 'create']);
        Route::post('/', [kategoricontroller::class, 'store']);
        Route::get('/create_ajax', [kategoricontroller::class, 'create_ajax']); //menampilkan halaman form tambah user ajax
        Route::post('/ajax', [kategoricontroller::class, 'store_ajax']); //meyimpan data user baru ajax
        Route::get('/{id}', [kategoricontroller::class, 'show']);
        Route::get('/{id}/edit', [kategoricontroller::class, 'edit']);
        Route::put('/{id}', [kategoricontroller::class, 'update']);
        Route::get('/{id}/edit_ajax', [kategoricontroller::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [kategoricontroller::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [kategoricontroller::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [kategoricontroller::class, 'delete_ajax']);
        Route::get('/{id}/show_ajax', [kategoricontroller::class, 'show_ajax']);
        Route::delete('/{id}', [kategoricontroller::class, 'destroy']);
        Route::get('/import', [kategoricontroller::class, 'import']);
        Route::post('/import_ajax', [kategoricontroller::class, 'import_ajax']);
        Route::get('/export_excel', [kategoricontroller::class, 'export_excel']);
        Route::get('/export_pdf', [kategoricontroller::class, 'export_pdf']);
    });

    //route barang
    Route::group(['prefix' => 'barang', 'middleware' => 'authorize:ADM,MNG'], function () {
        Route::get('/', [barangcontroller::class, 'index']);
        Route::post('/list', [barangcontroller::class, 'list']);
        Route::get('/create', [barangcontroller::class, 'create']);
        Route::post('/', [barangcontroller::class, 'store']);
        Route::get('/create_ajax', [barangcontroller::class, 'create_ajax']); //menampilkan halaman form tambah user ajax
        Route::post('/ajax', [barangcontroller::class, 'store_ajax']); //meyimpan data user baru ajax
        Route::get('/{id}', [barangcontroller::class, 'show']);
        Route::get('/{id}/edit', [barangcontroller::class, 'edit']);
        Route::put('/{id}', [barangcontroller::class, 'update']);
        Route::get('/{id}/edit_ajax', [barangcontroller::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [barangcontroller::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [barangcontroller::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [barangcontroller::class, 'delete_ajax']);
        Route::get('/{id}/show_ajax', [barangcontroller::class, 'show_ajax']);
        Route::delete('/{id}', [barangcontroller::class, 'destroy']);
        Route::get('/import', [barangcontroller::class, 'import']);
        Route::post('/import_ajax', [barangcontroller::class, 'import_ajax']);
        Route::get('/export_excel', [barangcontroller::class, 'export_excel']);
        Route::get('/export_pdf', [barangcontroller::class, 'export_pdf']);
    });

    //route supplier
    Route::group(['prefix' => 'supplier', 'middleware' => 'authorize:ADM,MNG,STF'], function () {
        Route::get('/', [suppliercontroller::class, 'index']);
        Route::post('/list', [suppliercontroller::class, 'list']);
        Route::get('/create', [suppliercontroller::class, 'create']);
        Route::post('/', [suppliercontroller::class, 'store']);
        Route::get('/create_ajax', [suppliercontroller::class, 'create_ajax']); //menampilkan halaman form tambah user ajax
        Route::post('/ajax', [suppliercontroller::class, 'store_ajax']); //meyimpan data user baru ajax
        Route::get('/{id}', [suppliercontroller::class, 'show']);
        Route::get('/{id}/edit', [suppliercontroller::class, 'edit']);
        Route::put('/{id}', [suppliercontroller::class, 'update']);
        Route::get('/{id}/edit_ajax', [suppliercontroller::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [suppliercontroller::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [suppliercontroller::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [suppliercontroller::class, 'delete_ajax']);
        Route::get('/{id}/show_ajax', [suppliercontroller::class, 'show_ajax']);
        Route::delete('/{id}', [suppliercontroller::class, 'destroy']);
        Route::get('/import', [suppliercontroller::class, 'import']);
        Route::post('/import_ajax', [suppliercontroller::class, 'import_ajax']);
        Route::get('/export_excel', [suppliercontroller::class, 'export_excel']);
        Route::get('/export_pdf', [suppliercontroller::class, 'export_pdf']);
    });

    //route stok
    Route::group(['prefix' => 'stok','middleware' => 'authorize:ADM,MNG,STF,GDA'], function () {
        Route::get('/', [stokcontroller::class, 'index']);
        Route::post('/list', [stokcontroller::class, 'list']);
        Route::get('/create', [stokcontroller::class, 'create']);
        Route::post('/', [stokcontroller::class, 'store']);
        Route::get('/create_ajax', [stokcontroller::class, 'create_ajax']); //menampilkan halaman form tambah user ajax
        Route::post('/ajax', [stokcontroller::class, 'store_ajax']); //meyimpan data user baru ajax
        Route::get('/{id}', [stokcontroller::class, 'show']);
        Route::get('/{id}/edit', [stokcontroller::class, 'edit']);
        Route::put('/{id}', [stokcontroller::class, 'update']);
        Route::get('/{id}/edit_ajax', [stokcontroller::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [stokcontroller::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [stokcontroller::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [stokcontroller::class, 'delete_ajax']);
        Route::get('/{id}/show_ajax', [stokcontroller::class, 'show_ajax']);
        Route::delete('/{id}', [stokcontroller::class, 'destroy']);
        Route::get('/import', [stokcontroller::class, 'import']);;
        Route::post('/import_ajax', [stokcontroller::class, 'import_ajax']);
        Route::get('/export_excel', [stokcontroller::class, 'export_excel']);
        Route::get('/export_pdf', [stokcontroller::class, 'export_pdf']);
    });

    Route::group(['prefix' => 'penjualan','middleware' => 'authorize:ADM,MNG,STF,GDA'], function () {
        Route::get('/', [transaksicontroller::class, 'index']);
        Route::post('/list', [transaksicontroller::class, 'list']);
        Route::get('/create_ajax', [transaksicontroller::class, 'create_ajax']); //menampilkan halaman form tambah user ajax
        Route::post('/ajax', [transaksicontroller::class, 'store_ajax']); //meyimpan data user baru ajax
        Route::get('/{id}/edit_ajax', [transaksicontroller::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [transaksicontroller::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [transaksicontroller::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [transaksicontroller::class, 'delete_ajax']);
        Route::get('/{id}/show_ajax', [transaksicontroller::class, 'show_ajax']);
        Route::get('/import', [transaksicontroller::class, 'import']);;
        Route::post('/import_ajax', [transaksicontroller::class, 'import_ajax']);
        Route::get('/export_excel', [transaksicontroller::class, 'export_excel']);
        Route::get('/export_pdf', [transaksicontroller::class, 'export_pdf']);
        Route::get('/getHarga/{id}', [transaksicontroller::class, 'getHarga']);
        Route::get('/getStok/{id}', [transaksicontroller::class, 'getStok']);
        Route::get('/getAvatar/{id}', [transaksicontroller::class, 'getAvatar']);
    });

    Route::group(['prefix' => 'profile','middleware' => 'authorize:ADM,MNG,STF,GDA'], function () {
        Route::get('/edit', [profilecontroller::class, 'edit']);
        Route::put('/update-avatar', [profilecontroller::class, 'update']);
        Route::put('/update', [profilecontroller::class, 'updateinfo']);
        Route::delete('/delete', [profilecontroller::class, 'delete']);
    });
});
