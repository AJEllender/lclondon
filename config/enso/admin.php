<?php

return [
    /**
     * Show a message in the admin area when the environment is not production.
     *
     * This will warn admins that changes may not be visible on the live site.
     */
    'show_not_production_warning' => env('ENSO_SHOW_NOT_PRODUCTION_MESSAGE', true),

    'menu' => [
        'logo' => '/img/enso/enso-square-logo.png',
    ]
];
