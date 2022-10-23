<?php

return [

    /**
     * Class of the Crud Config for Events.
     */
    'config' => App\Crud\Event::class,

    /**
     * Class of the Crud Controller for Events.
     */
    'controller' => App\Http\Controllers\Admin\EventController::class,

    /**
     * Whether to add a clone button the Pages index actions
     */
    'enable_cloning' => true,

    /**
     * Properties for the Ensō menu item for Events.
     */
    'menuitem' => [
        'icon' => 'fa fa-calendar-o',
        'label' => 'Events',
        'route' => ['admin.events.index'],
    ],

    /**
     * Class of the Crud Model for Events.
     */
    'model' => App\Models\Event::class,
];
