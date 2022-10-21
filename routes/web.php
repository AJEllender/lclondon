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

Auth::routes();

Route::group(['middleware' => 'enso'], function () {
    EnsoCrud::crudRoutes('admin/pages', 'page', 'admin.pages');
    Route::get('admin')->uses([\App\Http\Controllers\Admin\DashboardController::class, 'index']);
});

Route::group(['middleware' => 'holding-page'], function () {
    Route::get('/')->uses([\App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Duplicate route for post-login/register redirection
    Route::get('home')->uses([\App\Http\Controllers\HomeController::class, 'redirect']);

    Route::get('{page}')->uses([\App\Http\Controllers\PageController::class, 'show'])->name('pages.show');
});
