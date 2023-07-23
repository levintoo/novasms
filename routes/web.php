<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SendSMSController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome',[
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::middleware('auth')->group(function () {


    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
    });

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    Route::controller(SendSMSController::class)->group(function () {
        Route::get('/sendsms', 'index')->name('send-sms');
        Route::post('/sendsms', 'store')->name('send-sms.store');
    });

    Route::controller(ContactController::class)->group(function () {
        Route::get('/contacts', 'index')->name('contacts');
        Route::get('/contact/create', 'create')->name('contact.create');
        Route::post('/contact/create', 'store')->name('contact.store');
        Route::delete('/contact/{id}/delete', 'destroy')->name('contact.delete');
        Route::get('/contact/{id}/edit', 'edit')->name('contact.edit');
        Route::patch('/contact/{id}/edit', 'update')->name('contact.update');
    });
    Route::controller(GroupController::class)->group(function () {
        Route::get('/groups', 'index')->name('groups');
        Route::get('/group/create', 'create')->name('group.create');
        Route::post('/group/create', 'store')->name('group.store');
        Route::delete('/group/{id}/delete', 'destroy')->name('group.delete');
        Route::get('/group/{id}/edit', 'edit')->name('group.edit');
        Route::get('/group/{id}/show', 'show')->name('group.show');
        Route::patch('/group/{id}/edit', 'update')->name('group.update');
    });

});


require __DIR__.'/auth.php';
