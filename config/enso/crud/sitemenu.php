<?php

return [

    /**
     * Class of the Crud Config for Site Menus.
     */
    'config' => Yadda\Enso\SiteMenus\Crud\Menu::class,

    /**
     * Class of the Crud Controller for Site Menus.
     */
    'controller' => Yadda\Enso\SiteMenus\Contracts\MenuController::class,

    /**
     * Whether to add a clone button the Site Menus index actions
     */
    'enable_cloning' => false,

    /**
     * Properties for the Ensō menu item for Site Menus.
     */
    'menuitem' => [
        'icon' => 'fa fa-user-o',
        'label' => 'Menus',
        'route' => ['admin.site-menus.index'],
    ],

    /**
     * Class of the Crud Model for Site Menus.
     */
    'model' => Yadda\Enso\SiteMenus\Menu::class,

];
