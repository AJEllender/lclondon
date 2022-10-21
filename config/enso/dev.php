<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Responsive Helper
    |--------------------------------------------------------------------------
    |
    | Whether or not to show the responsive helper in the bottom right hand
    | corner of the window. By default this will show when debug mode is
    | enabled but you can force it to be disabled by setting
    | HIDE_RESPONSIVE_HELPER=true in your .env file
    |
    */
    'show_responsive_helper' => env('APP_DEBUG', false) && !env('HIDE_RESPONSIVE_HELPER', false),

];
