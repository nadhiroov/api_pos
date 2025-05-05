<?php

use App\Http\Middleware\isLogin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthWeb;
use App\Http\Controllers\Web\CategoryWeb;
use App\Http\Controllers\Web\Dashboard;

Route::get('/login', [AuthWeb::class, 'loginPage']);
Route::get('/register', function () {
    return view('auth.register');
});
Route::get('/forgot', function () {
    return view('auth.forgot');
});
Route::post('/authenticate', [AuthWeb::class, 'login']);
Route::get('/logout', [AuthWeb::class, 'logout']);

Route::middleware(isLogin::class)->group(
    function () {
        Route::get('/', [Dashboard::class, 'dashboard']);
        Route::get('/dashboard', [Dashboard::class, 'dashboard']);
        // Route::get('/category', [CategoryWeb::class, 'index']);
        // Route::get('/category/data', [CategoryWeb::class, 'getData']);
        // Route::get('/category/edit/{id}', [CategoryWeb::class, 'getData']);
        Route::get('/category/add', [CategoryWeb::class, 'add']);
        Route::get('/category/{id}/edit', [CategoryWeb::class, 'edit']);
        // Route::post('/category/store', [CategoryWeb::class, 'store']);
    }
);
Route::resource('category', \App\Http\Controllers\Web\CategoryWeb::class)
    ->middleware('auth');
