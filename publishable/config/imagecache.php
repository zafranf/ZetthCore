<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Name of route
    |--------------------------------------------------------------------------
    |
    | Enter the routes name to enable dynamic imagecache manipulation.
    | This handle will define the first part of the URI:
    |
    | {route}/{template}/{filename}
    |
    | Examples: "images", "img/cache"
    |
     */

    'route' => 'imache',

    /*
    |--------------------------------------------------------------------------
    | Storage paths
    |--------------------------------------------------------------------------
    |
    | The following paths will be searched for the image filename, submitted
    | by URI.
    |
    | Define as many directories as you like.
    |
     */

    'paths' => [
        storage_path('app/public/assets/images'),
        resource_path('themes'),
        base_path('vendor/zafranf/zetthcore/src/resources/themes'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Manipulation templates
    |--------------------------------------------------------------------------
    |
    | Here you may specify your own manipulation filter templates.
    | The keys of this array will define which templates
    | are available in the URI:
    |
    | {route}/{template}/{filename}
    |
    | The values of this array will define which filter class
    | will be applied, by its fully qualified name.
    |
     */

    'templates' => [
        'thumbnail' => 'ZetthCore\Vendor\ImageCache\Thumbnail',
        'small' => 'ZetthCore\Vendor\ImageCache\Small',
        'medium' => 'ZetthCore\Vendor\ImageCache\Medium',
        'large' => 'ZetthCore\Vendor\ImageCache\Large',
        'opengraph' => 'ZetthCore\Vendor\ImageCache\OpenGraph',
        'banner' => 'ZetthCore\Vendor\ImageCache\Banner',
        'post' => 'ZetthCore\Vendor\ImageCache\Post',
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Cache Lifetime
    |--------------------------------------------------------------------------
    |
    | Lifetime in minutes of the images handled by the imagecache route.
    |
     */

    'lifetime' => 518400,

    /*
    |--------------------------------------------------------------------------
    | Default size
    |--------------------------------------------------------------------------
    |
    | Default size for templates.
    |
     */

    'default' => [
        'thumbnail' => [
            'width' => 100,
            'height' => 75,
            'blur' => 2,
        ],
        'small' => [
            'width' => 200,
            'height' => 150,
            'blur' => 4,
        ],
        'medium' => [
            'width' => 400,
            'height' => 300,
            'blur' => 8,
        ],
        'large' => [
            'width' => 800,
            'height' => 600,
            'blur' => 16,
        ],
        'opengraph' => [
            'width' => 1200,
            'height' => 630,
            'blur' => 18,
        ],
    ],
];
