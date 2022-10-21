<?php

return [

    /**
     * Class of the Crud Config for Settings.
     */
    'config' => Yadda\Enso\Settings\Contracts\Crud::class,

    /**
     * Class of the Crud Controller for Settings.
     */
    'controller' => Yadda\Enso\Settings\Contracts\Controller::class,

    /**
     * Properties for the Ensō menu item for Settings.
     */
    'menuitem' => [
        'icon' => 'fa fa-cog',
        'label' => 'Settings',
        'route' => ['admin.settings.index'],
    ],

    /**
     * Class of the Crud Model for Settings.
     */
    'model' => Yadda\Enso\Settings\Contracts\Model::class,

];
