<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\JobExecutionController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ScheduleMonitorTaskController;
use App\Http\Controllers\ScheduleMonitorTaskLogItemController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


Route::resource('items', ItemController::class);
Route::resource('recipes', RecipeController::class);
Route::controller(ScheduleMonitorTaskController::class)->group(function () {
  Route::get('/schedule-monitor', 'index');
  Route::get('/schedule-monitor/{scheduleMonitorTask}', 'show');
});
Route::controller(ScheduleMonitorTaskLogItemController::class)->group(function () {
  Route::get('/schedule-monitor/{scheduleMonitorTask}/logs', 'index')->scopeBindings();
  Route::get('/schedule-monitor/{scheduleMonitorTask}/logs/{scheduleMonitorTaskLogItem}', 'show')->scopeBindings();
});

require __DIR__.'/auth.php';
