<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::controller('AuthController')->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
});

Route::controller('TaskController')->prefix('tasks')->group(function () {
    Route::get('/', 'index');
    Route::get('filter', 'filter');
    Route::get('{task}', 'show');
});

Route::controller('StackController')->prefix('tech-stacks')->group(function () {
    Route::get('search', 'search');
});

Route::middleware('auth:api')->group(function () {
    Route::post('logout', 'AuthController@logout');
});
