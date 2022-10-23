<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Yadda\Enso\Facades\EnsoMenu;

class EnsoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        EnsoMenu::addItems([
            array_merge(
                Config::get('enso.crud.eventtype.menuitem'),
                [
                    'items' => [
                        Config::get('enso.crud.event.menuitem'),
                    ],
                ]
            )
        ]);
    }
}
