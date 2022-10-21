<?php

return [

    /**
     * Class of the Crud Config for Categories.
     */
    'config' => Yadda\Enso\Categories\Crud\Category::class,

    /**
     * Class of the Crud Controller for Categories.
     */
    'controller' => Yadda\Enso\Categories\Controllers\Admin\CategoryController::class,

    /**
     * Whether to add a clone button the Categories index actions
     */
    'enable_cloning' => false,

    /**
     * Properties for the Ensō menu item for Categories.
     */
    'menuitem' => [
        'icon' => 'fa fa-tags',
        'label' => 'Categories',
        'route' => ['admin.categories.index'],
    ],

    /**
     * Class of the Crud Model for Categories.
     */
    'model' => Yadda\Enso\Categories\Models\Category::class,

];
