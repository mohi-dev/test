<?php

use App\Http\Controllers\AdvertiseController;
use App\Http\Controllers\CatController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use GuzzleHttp\Promise\TaskQueue;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('api/user')->group(function () {

    Route::match(['GET', 'POST'], '/login', [UserController::class, 'login']);

});

Route::prefix('api/advertise')->group(function () {

    Route::match(['GET', 'POST'], '/', [AdvertiseController::class, 'list']);
    Route::match(['GET', 'POST'], '/create', [AdvertiseController::class, 'store']);
    Route::match(['GET', 'POST'], '/update/{advertise}', [AdvertiseController::class, 'update']);
    Route::match(['GET', 'POST'], '/{advertise}', [AdvertiseController::class, 'get']);
    Route::match(['GET', 'POST'], '/{advertise}/delete', [AdvertiseController::class, 'destroy']);

});

Route::prefix('api/cat')->group(function () {

    Route::match(['GET', 'POST'], '/', [CatController::class, 'list']);
    Route::match(['GET', 'POST'], '/create', [CatController::class, 'store']);
    Route::match(['GET', 'POST'], '/update/{cat}', [CatController::class, 'update']);
    Route::match(['GET', 'POST'], '/{cat}', [CatController::class, 'get']);
    Route::match(['GET', 'POST'], '/{cat}/delete', [CatController::class, 'destroy']);

});

Route::prefix('api/item')->group(function () {

    Route::match(['GET', 'POST'], '/', [ItemController::class, 'list']);
    Route::match(['GET', 'POST'], '/create', [ItemController::class, 'store']);
    Route::match(['GET', 'POST'], '/update/{item}', [ItemController::class, 'update']);
    Route::match(['GET', 'POST'], '/{item}', [ItemController::class, 'get']);
    Route::match(['GET', 'POST'], '/{item}/delete', [ItemController::class, 'destroy']);

});