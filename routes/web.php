<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ChangeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Models\User;
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

Route::prefix('api-cafe')->group(function () {
    Route::post('login', [UserController::class, 'login']);
    Route::get('logout', [UserController::class, 'logout']);

    Route::get('users', [UserController::class, 'showUsers']); // функционал админа
    Route::post('users', [UserController::class, 'createUser']); // функционал админа
    Route::prefix('change')->group(function () {
        Route::post('/', [ChangeController::class, 'createChange']); // функционал админа
        Route::post('user', [ChangeController::class, 'addUser']); // функционал админа
        Route::get('{code}/orders', [OrderController::class, 'showOrders']); // функционал админа
    });

    Route::prefix('orders')->group(function () {
        Route::post('book', [OrderController::class, 'createOrder']);
        Route::post('{code}', [OrderController::class, 'showOrder']);
        Route::put('book/{code}', [OrderController::class, 'changeStatus']);
        Route::post('{code}/dish', [OrderController::class, 'addDish']);
        Route::delete('{code}/dish', [OrderController::class, 'deleteDish']);
    });
});
