<?php

return [

    'role_repository_concrete_class' => Yadda\Enso\Users\Repositories\RoleRepository::class,

    'permission_repository_concrete_class' => Yadda\Enso\Users\Repositories\PermissionRepository::class,

    'classes' => [
        'role' => Yadda\Enso\Users\Models\Role::class,
        'permission' => Yadda\Enso\Users\Models\Permission::class
    ]

];
