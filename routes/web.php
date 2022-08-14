<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\RecipeController;
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

Route::controller(ItemController::class)->group(function () {
    Route::get('/items/{item}', 'show');
    Route::get('/items', 'index');
});

Route::controller(RecipeController::class)->group(function () {
    Route::get('/recipes/{recipe}', 'show');
    Route::get('/recipes', 'index');
});
