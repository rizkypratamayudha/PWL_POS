<?php

use App\Http\Controllers\barangcontroller;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\kategoricontroller;
use App\Http\Controllers\levelcontroller;
use App\Http\Controllers\stokcontroller;
use App\Http\Controllers\suppliercontroller;
use App\Http\Controllers\usercontroller;
use App\Http\Controllers\welcomeController;
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


Route::get('/',[welcomeController::class,'index']);
//route user
Route::group(['prefix'=>'user'], function(){
    Route::get('/', [usercontroller::class, 'index']); //menampilkan halaman awal user
    Route::post('/list',[usercontroller::class, 'list']); //menampilkan data user dalam bentuk json untuk data tables
    Route::get('/create',[usercontroller::class,'create']); //menampilkan halaman form tambah user
    Route::post('/',[usercontroller::class,'store']); //menyimpan data user baru
    Route::get('/create_ajax',[usercontroller::class, 'create_ajax']);//menampilkan halaman form tambah user ajax
    Route::post('/ajax',[usercontroller::class, 'store_ajax']);//meyimpan data user baru ajax
    Route::get('/{id}',[usercontroller::class,'show']); //menampilkan detail user
    Route::get('/{id}/edit',[usercontroller::class,'edit']); //menampilkan halaman form edit
    Route::put('/{id}',[usercontroller::class,'update']);//meyimpan perubahan data user
    Route::get('/{id}/edit_ajax',[usercontroller::class, 'edit_ajax']);
    Route::put('/{id}/update_ajax',[usercontroller::class,'update_ajax']);
    Route::delete('/{id}',[usercontroller::class,'destroy']);//menghapus data user
});

//route level
Route::group(['prefix' =>'level'],function(){
    Route::get('/',[levelcontroller::class,'index']);
    Route::post('/list',[levelcontroller::class, 'list']);
    Route::get('/create',[levelcontroller::class,'create']);
    Route::post('/',[levelcontroller::class,'store']);
    Route::get('/{id}',[levelcontroller::class,'show']);
    Route::get('/{id}/edit',[levelcontroller::class,'edit']);
    Route::put('/{id}',[levelcontroller::class,'update']);
    Route::delete('/{id}',[levelcontroller::class,'destroy']);
});

//route kategori
Route::group(['prefix' =>'kategori'],function(){
    Route::get('/',[kategoricontroller::class,'index']);
    Route::post('/list',[kategoricontroller::class, 'list']);
    Route::get('/create',[kategoricontroller::class,'create']);
    Route::post('/',[kategoricontroller::class,'store']);
    Route::get('/{id}',[kategoricontroller::class,'show']);
    Route::get('/{id}/edit',[kategoricontroller::class,'edit']);
    Route::put('/{id}',[kategoricontroller::class,'update']);
    Route::delete('/{id}',[kategoricontroller::class,'destroy']);
});

//route barang
Route::group(['prefix' =>'barang'],function(){
    Route::get('/',[barangcontroller::class,'index']);
    Route::post('/list',[barangcontroller::class, 'list']);
    Route::get('/create',[barangcontroller::class,'create']);
    Route::post('/',[barangcontroller::class,'store']);
    Route::get('/{id}',[barangcontroller::class,'show']);
    Route::get('/{id}/edit',[barangcontroller::class,'edit']);
    Route::put('/{id}',[barangcontroller::class,'update']);
    Route::delete('/{id}',[barangcontroller::class,'destroy']);
});

//route supplier
Route::group(['prefix' =>'supplier'],function(){
    Route::get('/',[suppliercontroller::class,'index']);
    Route::post('/list',[suppliercontroller::class, 'list']);
    Route::get('/create',[suppliercontroller::class,'create']);
    Route::post('/',[suppliercontroller::class,'store']);
    Route::get('/{id}',[suppliercontroller::class,'show']);
    Route::get('/{id}/edit',[suppliercontroller::class,'edit']);
    Route::put('/{id}',[suppliercontroller::class,'update']);
    Route::delete('/{id}',[suppliercontroller::class,'destroy']);
});

//route stok
Route::group(['prefix' =>'stok'],function(){
    Route::get('/',[stokcontroller::class,'index']);
    Route::post('/list',[stokcontroller::class, 'list']);
    Route::get('/create',[stokcontroller::class,'create']);
    Route::post('/',[stokcontroller::class,'store']);
    Route::get('/{id}',[stokcontroller::class,'show']);
    Route::get('/{id}/edit',[stokcontroller::class,'edit']);
    Route::put('/{id}',[stokcontroller::class,'update']);
    Route::delete('/{id}',[stokcontroller::class,'destroy']);
});