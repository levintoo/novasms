<?php

use App\Http\Controllers\BatchProgressController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UploadContactsController;
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
    return \inertia('Welcome',[
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::middleware('auth')->group(function () {

    Route::controller(\App\Http\Controllers\DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
    });

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    Route::controller(ContactController::class)->group(function () {
        Route::get('/contacts', 'index')->name('contacts');
        Route::get('/contact/create', 'create')->name('contact.create');
        Route::post('/contact/create', 'store')->name('contact.store');
        Route::delete('/contact/{id}/delete', 'destroy')->name('contact.delete');
        Route::get('/contact/{id}/edit', 'edit')->name('contact.edit');
        Route::patch('/contact/{id}/edit', 'update')->name('contact.update');
    });

    Route::controller(UploadContactsController::class)->group(function () {
        Route::get('/contacts/upload', 'index')->name('contacts.create');
        Route::post('/contacts/upload', 'upload')->name('contacts.upload');
    });

    Route::controller(GroupController::class)->group(function () {
        Route::get('/groups', 'index')->name('groups');
        Route::get('/group/create', 'create')->name('group.create');
        Route::post('/group/create', 'store')->name('group.store');
        Route::delete('/group/{id}/delete', 'destroy')->name('group.delete');
        Route::get('/group/{id}/edit', 'edit')->name('group.edit');
        Route::patch('/group/{id}/edit', 'update')->name('group.update');
    });

    Route::controller(BatchProgressController::class)->group(function () {
        Route::get('/batch/{id}', 'index')->name('batch');
        Route::post('/batch/{id}/progress', 'getProgress')->name('batch.progress');
    });

    Route::controller(\App\Http\Controllers\SmsController::class)->group(function () {
        Route::get('/messages', 'index')->name('messages');
        Route::get('/sms/create', 'create')->name('sms.create');
        Route::delete('/message/{id}', 'destroy')->name('message.delete');
        Route::post('/contact/sms/send', 'sendToGroup')->name('sms.group.send');
        Route::post('/group/sms/send', 'sendToContact')->name('sms.contact.send');
    });

    Route::controller(\App\Http\Controllers\WalletController::class)->group(function () {
       Route::get('/wallet','index')->name('wallet');
       Route::post('/recharge/mpesa','top_up_with_mpesa')->name('wallet.top_up_with_mpesa');
    });

    Route::middleware(['can:manage users'])->prefix('admin')->group(function () {

        Route::controller(\App\Http\Controllers\Admin\DashboardController::class)->group(function () {
            Route::get('/', 'index')->name('admin.dashboard');
        });

        Route::controller(\App\Http\Controllers\Admin\UsersController::class)->group(function () {
            Route::get('/users', 'index')->name('admin.users');
            Route::get('/user/create', 'create')->name('admin.user.create');
            Route::post('/user/store', 'store')->name('admin.user.store');
            Route::get('/user/{id}/edit', 'edit')->name('admin.user.edit');
            Route::get('/user/{id}', 'show')->name('admin.user.show');
            Route::patch('/user/{user}', 'update')->name('admin.user.update');
            Route::delete('/user/{user}', 'destroy')->name('admin.user.delete');
            Route::patch('/user/{id}/restore', 'restore')->name('admin.user.restore');
            Route::patch('/user/{user}/password/reset', 'resetPassword')->name('admin.user.password.reset');
        });

        Route::controller(\App\Http\Controllers\Admin\ManageContactsController::class)->group(function () {
            Route::get('/contacts', 'index')->name('admin.contacts');
            Route::delete('/contact/{contact}', 'destroy')->name('admin.contact.delete');
            Route::patch('/contact/{id}', 'restore')->name('admin.contact.restore');
        })->middleware(['can:manage contacts']);

        Route::controller(\App\Http\Controllers\Admin\ManageGroupsController::class)->group(function () {
            Route::get('/groups', 'index')->name('admin.groups');
            Route::delete('/group/{group}', 'destroy')->name('admin.group.delete');
            Route::patch('/group/{id}', 'restore')->name('admin.group.restore');
        })->middleware(['can:manage groups']);

        Route::controller(\App\Http\Controllers\Admin\ManageSmsController::class)->group(function () {
            Route::get('/messages', 'index')->name('admin.messages');
            Route::delete('/message/{message}', 'destroy')->name('admin.message.delete');
            Route::patch('/message/{id}', 'restore')->name('admin.message.restore');
        })->middleware(['can:manage messages']);
    });

});

require __DIR__.'/auth.php';
