<?php

EnsoCrud::crudRoutes('events', 'event', 'events');
EnsoCrud::crudRoutes('event-types', 'eventtype', 'event-types');
EnsoCrud::crudRoutes('pages', 'page', 'pages');

Route::get('/')->uses([\App\Http\Controllers\Admin\DashboardController::class, 'index']);
