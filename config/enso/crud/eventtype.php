<?php

return [

    /**
     * Class of the Crud Config for EventTypes.
     */
    'config' => App\Crud\EventType::class,

    /**
     * Class of the Crud Controller for EventTypes.
     */
    'controller' => App\Http\Controllers\Admin\EventTypeController::class,

    /**
     * Properties for the Ensō menu item for EventTypes.
     */
    'menuitem' => [
        'icon' => 'fa fa-calendar',
        'label' => 'Event Types',
        'route' => ['admin.event-types.index'],
    ],

    /**
     * Class of the Crud Model for EventTypes.
     */
    'model' => App\Models\EventType::class,
];
