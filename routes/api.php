<?php

use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\LevelController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Monolog\Level;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/register',RegisterController::class)->name('register');
Route::post('/login',LoginController::class)->name('login');
Route::middleware('auth:api')->get('/user',function(Request $request){
    return $request->user();
});
Route::post('/logout',LogoutController::class)->name('logout');

Route::group(['prefix'=>'levels'],function(){
    Route::get('/',[LevelController::class,'index']);
    Route::post('/',[LevelController::class,'store']);
    Route::get('/{level}',[LevelController::class,'show']);
    Route::put('/{level}',[LevelController::class,'update']);
    Route::delete('/{level}',[LevelController::class,'destroy']);
});

Route::group(['prefix'=>'users'],function(){
    Route::get('/',[UserController::class,'index']);
    Route::post('/',[UserController::class,'store']);
    Route::get('/{id}',[UserController::class,'show']);
    Route::put('/{id}',[UserController::class,'update']);
    Route::delete('/{id}',[UserController::class,'destroy']);
});

Route::group(['prefix'=>'kategoris'],function(){
    Route::get('/',[KategoriController::class,'index']);
    Route::post('/',[KategoriController::class,'store']);
    Route::get('/{id}',[KategoriController::class,'show']);
    Route::put('/{id}',[KategoriController::class,'update']);
    Route::delete('/{id}',[KategoriController::class,'destroy']);
});

Route::group(['prefix'=>'barangs'],function(){
    Route::get('/',[BarangController::class,'index']);
    Route::post('/',[BarangController::class,'store']);
    Route::get('/{id}',[BarangController::class,'show']);
    Route::put('/{id}',[BarangController::class,'update']);
    Route::delete('/{id}',[BarangController::class,'destroy']);
});
