<?php

return [

    /**
     * Whether to cache the generated CRUD name list.
     */
    'cache_crud_name_list' => (bool) !env('APP_DEBUG', false),

    /**
     * Whether to cache Ensō settings
     */
    'cache_enso_settings' => (bool) !env('APP_DEBUG', false),

    /**
     * Currency for use in MoneyFields
     */
    'currency' => 'GBP',

    /**
     * Add extra fields to the settings crud by providing the names of classes
     * that implement Yadda\Enso\Settings\Contracts\ExtraSettings
     */
    'extra' => [
        Yadda\Enso\Crud\Forms\Rows\SignupForms\Generic::class,
        Yadda\Enso\Settings\Crud\ExtraFields\SocialAccounts::class
    ],

    /**
     * Base layout for Enso pages
     */
    'layout' => 'enso::layouts.app',

    /**
     * Path relative to /public in which to look for mix-manifest.json
     *
     * You may need to change this if you need to override the JS/CSS in
     * the admin area. You can compile the JS/CSS yourself in your regular
     * webpack.mix.js and then direct the system to use your existing
     * manifest by changing this setting to ''.
     */
    'manifest_directory' => 'enso',

    /**
     * Name of the property to use when labelling select options.
     */
    'select_label_by' => 'label',

    /**
     * Name of the property to use when identifying select options.
     */
    'select_track_by' => 'id',

];
