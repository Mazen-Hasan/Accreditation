<?php

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

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('main');


Route::group(['middleware' => 'role:event-admin'], function() {

    Route::resource('EventController', 'App\Http\Controllers\EventController');
    Route::get('/events', [App\Http\Controllers\EventController::class, 'index'])->name('events');
    Route::get('/event-add', [App\Http\Controllers\EventController::class, 'eventAdd'])->name('eventAdd');
    Route::get('/event-edit/{id}', [App\Http\Controllers\EventController::class, 'edit'])->name('eventEdit');
});

Route::group(['middleware' => 'role:super-admin'], function() {
//    Route::get('/events', [App\Http\Controllers\EventController::class, 'index'])->name('events');
//    Route::get('/event-add', [App\Http\Controllers\EventController::class, 'eventAdd'])->name('eventAdd');
//    Route::get('/event-edit/{id}', [App\Http\Controllers\EventController::class, 'edit'])->name('eventEdit');
    Route::get('/titles', [App\Http\Controllers\TitleController::class, 'index'])->name('titles');

//    Route::resource('dtable-posts', 'App\Http\Controllers\EventController');
//    Route::get('dtable-posts/destroy/{id}', 'App\Http\Controllers\EventController@destroy');

    Route::resource('titleController', 'App\Http\Controllers\TitleController');
    Route::get('titleController/destroy/{id}', 'App\Http\Controllers\TitleController@destroy');
    Route::get('titleController/changeStatus/{id}/{status}', 'App\Http\Controllers\TitleController@changeStatus');
    Route::resource('contactController', 'App\Http\Controllers\ContactController');
    Route::get('/contacts', [App\Http\Controllers\ContactController::class, 'index'])->name('contacts');
    Route::get('/contact-add', [App\Http\Controllers\ContactController::class, 'contactAdd'])->name('contactAdd');
    Route::get('/contact-edit/{id}', [App\Http\Controllers\ContactController::class, 'edit'])->name('contactEdit');
    Route::get('contactController/removeContactTitle/{contact_id}/{title_id}', 'App\Http\Controllers\ContactController@removeContactTitle');
    Route::get('contactController/storeContactTitle/{contact_id}/{title_id}', 'App\Http\Controllers\ContactController@storeContactTitle');
//    Route::get('contactController/storeContactTitle', 'App\Http\Controllers\ContactController@storeContactTitle');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
