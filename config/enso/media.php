<?php

return [

    /**
     * Must provide both width and height. Setting either to null will set the
     * resized image to be constrained by the remaing value, and then use
     * aspect ratio to deduce the other value.
     */
    'presets' => [
        'hero' => [1024, 441],
        'hero_lg' => [1920, 827],
        'hero_lg_2x' => [3840, 1653],
        'hero_mobile' => [768, 768],
        'hero_mobile_2x' => [1536, 1536],
        '16_9_1920' => [1920, 1080],
        '768_x' => [768, null],
        '1536_x' => [1536, null],
        '1024_x' => [1024, null],
        '1920_x' => [1920, null],
        '3840_x' => [3840, null],
        '1024_x' => [1024, null],
    ],

    /**
     * Light/Dark area definitions. These should be arrays of 4 values:
     *  Top left pixel of area (x-axis)
     *  Top left pixel of area (y-axis)
     *  Width of area
     *  Height of area
     *
     * Bear in mind that the [0, 0] is in the top left corner.
     */
    'light_dark_areas' => [
        // 'full' => [0, 0, '100%', '100%'],
    ],

    'memory_limit' => env('ENSO_MEDIA_MEMORY_LIMIT', '1G'),

    /**
     * Max size in MB of a single file uploaded to the media system
     */
    'max_file_size' => env('ENSO_MEDIA_MAX_FILE_SIZE', '15'),

    /**
     * Whether or not to show the "this file is in use and can not be deleted"
     * message. If false a generic "this may be in use" message will be
     * shown instead.
     */
    'show_file_in_use_message' => env('ENSO_MEDIA_FILE_IN_USE_DIALOG', false),

    /**
     * Default disk to store uploaded media on
     */
    'disk' => 'public',

    /**
     * Directory in which to store uploaded media files
     */
    'directory' => 'media',

    /**
     * Sub Directory of the above directory in which to store imported media files
     */
    'import_directory' => 'imports',

    /**
     * Whether the site should attempt to maintain a files list
     * in the database as a list of file model relationships.
     */
    'maintain_file_list' => true,

    /**
     * If true, resized images will be run through Spatie Image Optimizer
     */
    'optimize_images' => env('ENSO_OPTIMIZE_IMAGES', false),

    /**
     * Accepted MIME types and the media type they correspond to.
     */
    'mime_types' => [
        'image/jpeg'      => 'image',
        'image/gif'       => 'image',
        'image/png'       => 'image',
        'image/pjpeg'     => 'image',
        'image/png'       => 'image',
        'audio/x-aac'     => 'audio',
        'audio/x-aiff'    => 'audio',
        'audio/x-ms-wma'  => 'audio',
        'audio/mpeg'      => 'audio',
        'audio/mp4'       => 'audio',
        'audio/ogg'       => 'audio',
        'audio/webm'      => 'audio',
        'audio/x-wav'     => 'audio',
        'video/3gpp'      => 'video',
        'video/3gpp2'     => 'video',
        'video/x-msvideo' => 'video',
        'video/x-f4v'     => 'video',
        'video/x-flv'     => 'video',
        'video/h261'      => 'video',
        'video/h263'      => 'video',
        'video/h264'      => 'video',
        'video/x-m4v'     => 'video',
        'video/x-ms-wmv'  => 'video',
        'video/mpeg'      => 'video',
        'video/mp4'       => 'video',
        'video/ogg'       => 'video',
        'video/webm'      => 'video',
        'video/quicktime' => 'video',
    ],

];
