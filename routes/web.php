<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
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

    Route::controller(GroupController::class)

        ->prefix('group')

        ->name('group.')

        ->group(function () {

            Route::get('/', 'index')->name('index');

            Route::get('create', 'create')->name('create');

            Route::post('create', 'store')->name('store');

            Route::get('edit/{group}', 'edit')->name('edit');

            Route::patch('edit/{group}', 'update')->name('update');

            Route::delete('{group}', 'destroy')->name('delete');

    });

    Route::controller(ContactController::class)

        ->prefix('contact')

        ->name('contact.')

        ->group(function () {

            Route::get('/', 'index')->name('index');

            Route::get('create', 'InputCreate')->name('input.create');

            Route::post('create', 'inputStore')->name('input.store');

            Route::get('upload', 'uploadCreate')->name('upload.create');

            Route::post('upload', 'UploadStore')->name('upload.store');

            Route::get('edit/{contact}', 'edit')->name('edit');

            Route::patch('edit/{contact}', 'update')->name('update');

            Route::delete('{contact}', 'destroy')->name('delete');

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
