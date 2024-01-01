<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\Admin\GroupController as AdminGroupController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PendingJobController;
use App\Http\Controllers\Admin\PendingJobController as AdminPendingJobController;
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
    return view('Home');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::controller(DashboardController::class)

        ->prefix('dashboard')

        ->name('dashboard')

        ->group(function () {

        Route::get('/', 'index');

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

    Route::controller(PendingJobController::class)

        ->prefix('pending-jobs')

        ->name('job.')

        ->group(function () {

            Route::get('/', 'index')->name('index');

    });

    // admin routes
    Route::middleware(['can:manage users'])

        ->prefix('admin')

        ->name('admin.')

        ->group(function () {

            Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');

            Route::controller(AdminGroupController::class)

                ->prefix('group')

                ->name('group.')

                ->group(function () {

                    Route::get('/', 'index')->name('index');

                    Route::delete('{group}', 'destroy')->name('delete');

                    Route::patch('restore/{group}', 'restore')->name('restore');

            });

            Route::controller(AdminContactController::class)

                ->prefix('contact')

                ->name('contact.')

                ->group(function () {

                    Route::get('/', 'index')->name('index');

                    Route::delete('{contact}', 'destroy')->name('delete');

                    Route::patch('restore/{contact}', 'restore')->name('restore');

            });

            Route::controller(AdminUserController::class)

                ->prefix('user')

                ->name('user.')

                ->group(function () {

                    Route::get('/', 'index')->name('index');

                    Route::get('create', 'create')->name('create');

                    Route::get('{userId}', 'show')->name('show');

                    Route::post('create', 'store')->name('store');

                    Route::get('{userId}/edit', 'edit')->name('edit');

                    Route::patch('{user}/edit', 'update')->name('update');

                    Route::delete('{user}', 'destroy')->name('delete');

                    Route::patch('{id}/restore', 'restore')->name('restore');

                    Route::patch('{user}/password/reset', 'resetPassword')->name('password.reset');
            });

            Route::controller(AdminPendingJobController::class)

                ->prefix('pending-jobs')

                ->name('jobs.')

                ->group(function () {

                    Route::get('/', 'index')->name('index');
            });
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
