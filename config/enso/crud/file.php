<?php

return [

    /**
     * Class of the Crud Config for Files.
     */
    'config' => Yadda\Enso\Media\Crud\File::class,

    /**
     * Class of the Crud Controller for Files.
     */
    'controller' => Yadda\Enso\Media\Controllers\FileController::class,

    /**
     * Whether to add a clone button the Files index actions
     */
    'enable_cloning' => false,

    /**
     * Properties for the Ensō menu item for Files.
     */
    'menuitem' => [
        'icon' => 'fa fa-picture-o',
        'label' => 'Media',
        'route' => ['admin.media.index'],
    ],

    /**
     * Class of the Crud Model for Files.
     */
    'model' => Yadda\Enso\Media\Models\MediaFile::class,

];
