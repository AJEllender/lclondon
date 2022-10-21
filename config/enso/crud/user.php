<?php

return [

    /**
     * Class of the Crud Config for Users.
     */
    'config' => App\Crud\User::class,

    /**
     * Class of the Crud Controller for Users.
     */
    'controller' => Yadda\Enso\Users\Controllers\UserController::class,

    /**
     * Whether to add a clone button the Users index actions
     */
    'enable_cloning' => false,

    /**
     * Properties for the Ensō menu item for Users.
     */
    'menuitem' => [
        'icon' => 'fa fa-user-o',
        'label' => 'Users',
        'route' => ['admin.users.index'],
    ],

    /**
     * Class of the Crud Model for Users.
     */
    'model' => App\Models\User::class,

];
