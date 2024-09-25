<?php

use App\Http\Controllers\dashboardController;
use App\Http\Controllers\kategoricontroller;
use App\Http\Controllers\levelcontroller;
use App\Http\Controllers\usercontroller;
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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/level',[levelcontroller::class, 'index']);
// Route::get('/kategori',[kategoricontroller::class,'index']);

// Route::get('/user',[usercontroller::class,'index']);

// Route::get('/user/tambah',[usercontroller::class, 'tambah']);
// Route::post('/user/tambah_simpan',[usercontroller::class,'tambah_simpan']);

// Route::get('/user/ubah/{id}',[usercontroller::class,'ubah']);
// Route::put('/user/ubah_simpan/{id}',[usercontroller::class,'ubah_simpan']);

// Route::get('/user/hapus/{id}',[usercontroller::class,'hapus']);

// Route::get('/indexrelationship',[usercontroller::class,'indexrelationship']);

Route::get('/dashboard',[dashboardController::class,'index']);