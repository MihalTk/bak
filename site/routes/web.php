<?php

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

Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');

Route::get('/next', [App\Http\Controllers\NextController::class, 'index'])->name('next');

Route::post('/next', [App\Http\Controllers\NextController::class, 'index'])->name('next');

Route::post('/decideRoute', [App\Http\Controllers\NextController::class, 'decide'])->name('decide');

Route::post('/change_emailRoute', [App\Http\Controllers\ProfileController::class, 'changeemail'])->name('change-email');

Route::post('/change-heightRoute', [App\Http\Controllers\ProfileController::class, 'changeheight'])->name('change-height');

Route::post('/change-weightRoute', [App\Http\Controllers\ProfileController::class, 'changeweight'])->name('change-weight');

Route::post('/change-daysRoute', [App\Http\Controllers\ProfileController::class, 'changedays'])->name('change-days');

Route::post('/change-nameRoute', [App\Http\Controllers\ProfileController::class, 'changename'])->name('change-name');

Route::post('/delete-nameRoute', [App\Http\Controllers\ProfileController::class, 'deletename'])->name('delete-name');

Route::post('/change-experienceRoute', [App\Http\Controllers\ProfileController::class, 'changeexperience'])->name('change-experience');

Route::post('/change-equipmentsRoute', [App\Http\Controllers\ProfileController::class, 'changeequipments'])->name('change-equipments');

Route::post('/change-goalRoute', [App\Http\Controllers\ProfileController::class, 'changegoal'])->name('change-goal');

Route::post('/delete-indgoalRoute', [App\Http\Controllers\ProfileController::class, 'deleteindgoal'])->name('delete-indgoal');

Route::post('/add-indgoalRoute', [App\Http\Controllers\ProfileController::class, 'addindgoal'])->name('add-indgoal');