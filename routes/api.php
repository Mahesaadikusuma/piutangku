<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\Api\PiutangController;
use App\Http\Controllers\Api\ProductsController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\PermissionsController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\PiutangProductsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/midtrans/callback', [MidtransController::class, 'notificationHandler'])->name('webhook.midtrans');


Route::prefix('piutang')
    ->middleware('auth:sanctum')
    ->controller(PiutangController::class)->group(function () {
        Route::get('/piutangs', 'piutangs');
        Route::post('/create', 'create');
        Route::post('/update/{piutang:id}', 'update');
        Route::get('/detail/{piutang:id}', 'show');
        Route::delete('/delete/{piutang:id}', 'delete');
    });
Route::prefix('piutang-product')
    ->middleware('auth:sanctum')
    ->controller(PiutangProductsController::class)->group(function () {
        Route::get('/piutangs', 'piutangs');
        Route::post('/create', 'create');
        Route::post('/update/{piutang:id}', 'update');
        Route::get('/detail/{piutang:id}', 'show');
        Route::delete('/delete/{piutang:id}', 'delete');
    });
Route::prefix('transaction')
    ->middleware('auth:sanctum')
    ->controller(TransactionController::class)->group(function () {
        Route::get('/transactions', 'transactions');
        Route::post('/create', 'create');
        Route::post('/update/{piutang:id}', 'update');
        Route::delete('/delete/{piutang:id}', 'delete');
    });

Route::prefix('permission')
    ->middleware('auth:sanctum')
    ->controller(PermissionsController::class)->group(function () {
        Route::get('/permissions', 'permissions');
        Route::post('/create', 'create');
        Route::post('/update/{permission:id}', 'update');
        Route::delete('/delete/{permission:id}', 'delete');
    });
Route::prefix('role')
    ->middleware('auth:sanctum')
    ->controller(RoleController::class)->group(function () {
        Route::get('/roles', 'roles');
        Route::post('/create', 'create');
        Route::post('/update/{permission:id}', 'update');
        Route::delete('/delete/{permission:id}', 'delete');
    });

Route::prefix('auth')->controller(UserController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');


    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', 'logout');
        Route::get('/user', 'fetchUser');
        Route::get('/users', 'users');
    });
});

Route::prefix('product')
    ->middleware('auth:sanctum')
    ->controller(ProductsController::class)->group(function () {
        Route::get('/products', 'products');
        Route::post('/create', 'create');
        Route::post('/update/{product:id}', 'update');
        Route::delete('/delete/{product:id}', 'delete');
    });

Route::prefix('category')
    ->middleware('auth:sanctum')
    ->controller(CategoriesController::class)->group(function () {
        Route::get('/categories', 'categories');
        Route::post('/create', 'create');
        Route::post('/update/{category:id}', 'update');
        Route::delete('/delete/{category:id}', 'delete');
    });
