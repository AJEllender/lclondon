<?php

return [

    /**
     * Class of the Crud Config for Newsletters.
     */
    'config' => Yadda\Enso\Newsletter\Crud\Newsletter::class,

    /**
     * Class of the Crud Controller for Newsletters.
     */
    'controller' => Yadda\Enso\Newsletter\Controllers\Admin\NewsletterController::class,

    /**
     * Properties for the Ensō menu item for Newsletters.
     */
    'menuitem' => [
        'icon' => 'fa fa-newspaper-o',
        'label' => 'Newsletter Signups',
        'route' => ['admin.newsletters.index'],
    ],

    /**
     * Class of the Crud Model for Newsletters.
     */
    'model' => Yadda\Enso\Newsletter\Models\Newsletter::class,

];
