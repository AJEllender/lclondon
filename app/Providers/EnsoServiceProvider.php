<?php

namespace App\Providers;

use App\Crud\Handlers\FlexibleField;
use App\Crud\MenuCrud;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Yadda\Enso\Crud\Contracts\FlexibleFieldHandler;
use Yadda\Enso\Facades\EnsoMenu;
use Yadda\Enso\SiteMenus\Contracts\MenuCrud as ContractsMenuCrud;

class EnsoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ContractsMenuCrud::class, MenuCrud::class);
        $this->app->bind(FlexibleFieldHandler::class, FlexibleField::class);
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
