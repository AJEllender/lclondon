<?php

use Illuminate\Support\Facades\Route;
use Yadda\Enso\Facades\EnsoCrud;
use Yadda\Enso\Newsletter\Facades\EnsoNewsletter;

EnsoCrud::crudRoutes('events', 'event', 'events');
EnsoCrud::crudRoutes('event-types', 'eventtype', 'event-types');
EnsoCrud::crudRoutes('pages', 'page', 'pages');

EnsoNewsletter::crudRoutes('newsletters', 'ensonewsletter', 'newsletters');

Route::get('/')->uses([\App\Http\Controllers\Admin\DashboardController::class, 'index']);
