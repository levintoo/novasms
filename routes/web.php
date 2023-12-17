<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
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
    return inertia('Welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::controller(DashboardController::class)

        ->prefix('dashboard')

        ->name('dashboard.')

        ->group(function () {

        Route::get('/', 'index')->name('index');

        Route::patch('/', 'update')->name('update');

        Route::delete('/', 'destroy')->name('destroy');

    });

    Route::controller(MessageController::class)

        ->prefix('message')

        ->name('message.')

        ->group(function () {

        Route::get('/', 'index')->name('index');

        Route::get('compose', 'create')->name('compose');

        Route::post('send', 'send')->name('send');

    });

});

Route::controller(ProfileController::class)

    ->middleware('auth')

    ->prefix('profile')

    ->name('profile.')

    ->group(function () {

    Route::get('/', 'edit')->name('edit');

    Route::patch('/', 'update')->name('update');

    Route::delete('/', 'destroy')->name('destroy');

});

require __DIR__.'/auth.php';
