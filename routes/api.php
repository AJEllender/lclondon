<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['globalscopes'])->group(function () {
    Route::get('events')->uses([\App\Http\Controllers\Api\EventController::class, 'index'])->name('events.index');
});
