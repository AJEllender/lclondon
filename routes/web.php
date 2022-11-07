<?php

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

Auth::routes(['register' => false]);

Route::middleware(['holding-page', 'globalscopes'])->group(function () {
    Route::get('/')->uses([\App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('events-types')->uses([\App\Http\Controllers\EventTypeController::class, 'index'])->name('event-types.index');
    Route::get('events/{event_type}/{event}')->uses([\App\Http\Controllers\EventController::class, 'show'])->name('events.show');
    Route::get('events/{event_type}')->uses([\App\Http\Controllers\EventTypeController::class, 'show'])->name('event-types.show');
    Route::get('events')->uses([\App\Http\Controllers\EventController::class, 'index'])->name('events.index');

    // Duplicate route for post-login/register redirection
    Route::get('home')->uses([\App\Http\Controllers\HomeController::class, 'redirect']);
    Route::get('{page}')->uses([\App\Http\Controllers\PageController::class, 'show'])->name('pages.show');
});
