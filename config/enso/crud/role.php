<?php

return [

    /**
     * Class of the Crud Config for Roles.
     */
    'config' => App\Crud\Role::class,

    /**
     * Class of the Crud Controller for Roles.
     */
    'controller' => Yadda\Enso\Users\Controllers\RoleController::class,

    /**
     * Whether to add a clone button the Roles index actions
     */
    'enable_cloning' => false,

    /**
     * Properties for the Ensō menu item for Roles.
     */
    'menuitem' => [
        'icon' => 'fa fa-id-card-o',
        'label' => 'Roles',
        'route' => ['admin.roles.index'],
    ],

    /**
     * Class of the Crud Model for Roles.
     */
    'model' => App\Models\Role::class,

];
