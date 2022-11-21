<?php

return [

    'default-background-color' => 'light',

    'default-background-texture' => 'none',

    'default-font-color' => 'dark',

    'background-colors' => [
        'white' => 'White',
        'light' => 'Light',
        'blue' => 'Blue',
        'dark' => 'Dark',
        'black' => 'Black',
    ],

    'background-textures' => [
        'none' => 'None',
        'leather' => 'Leather',
        'asphalt' => 'Asphalt',
    ],

    'font-colors' => [
        'light' => 'Light',
        'dark' => 'Dark',
    ],

    'rows' => [
        'buttonrow' => \App\Crud\Rows\ButtonRow::class,
        'calendarrow' => \App\Crud\Rows\CalendarRow::class,
        'contactrow' => \App\Crud\Rows\ContactRow::class,
        'eventhighlight' => \App\Crud\Rows\EventHighlightRow::class,
        'eventhighlights' => \App\Crud\Rows\EventHighlightsRow::class,
        'galleryrow' => \App\Crud\Rows\GalleryRow::class,
        'herorow' => \App\Crud\Rows\HeroRow::class,
        'imagerow' => \App\Crud\Rows\ImageRow::class,
        'infostriprow' => \App\Crud\Rows\InfoStripRow::class,
        'newsletter' => \App\Crud\Rows\NewsletterRow::class,
        'textimagerow' => \App\Crud\Rows\TextImageRow::class,
        'textrow' => \App\Crud\Rows\TextRow::class,
        'upcomingeventsrow' => \App\Crud\Rows\UpcomingEventsRow::class,
        'upcomingeventtypesrow' => \App\Crud\Rows\UpcomingEventTypesRow::class,
    ],

];
