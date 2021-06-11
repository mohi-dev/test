<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
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

Route::prefix('api/user')->group(function () {
    Route::match(['GET', 'POST'], '/getAccess', [UserController::class, 'getAccess']);
});

Route::prefix('api/post')->middleware(['acl'])->group(function () {
    Route::match(['GET', 'POST'], '/', [PostController::class, 'list']);
    Route::match(['GET', 'POST'], '/create', [PostController::class, 'create']);
    Route::match(['GET', 'POST'], '/{post}', [PostController::class, 'get']);
    Route::match(['PUT', 'POST', 'GET'], '/update/{post}', [PostController::class, 'update']);
    Route::match(['DELETE', 'GET', 'POST'], '/delete/{post}', [PostController::class, 'remove']);
});
