<?php

return [
    'name' => '2earn.cash',
    'manifest' => [
        'name' => env('APP_NAME', '2earn.cash'),
        'short_name' => '2earn.cash',
        'start_url' => '/',
        'background_color' => '#ffffff',
        'theme_color' => '#000000',
        'display' => 'standalone',
        'orientation'=> 'any',
        'status_bar'=> 'black',
        'icons' => [
            '72x72' => [
                'path' => '/assets/images/logo2earn.png',
                'purpose' => 'any'
            ],
            '96x96' => [
                'path' => '/assets/images/logo2earn.png',
                'purpose' => 'any'
            ],
            '128x128' => [
                'path' => '/assets/images/logo2earn.png',
                'purpose' => 'any'
            ],
            '144x144' => [
                'path' => '/assets/images/logo2earn.png',
                'purpose' => 'any'
            ],
            '152x152' => [
                'path' => '/assets/images/logo2earn.png',
                'purpose' => 'any'
            ],
            '192x192' => [
                'path' => '/assets/images/logo2earn.png',
                'purpose' => 'any'
            ],
            '384x384' => [
                'path' => '/assets/images/logo2earn.png',
                'purpose' => 'any'
            ],
            '512x512' => [
                'path' => '/assets/images/logo2earn.png',
                'purpose' => 'any'
            ],
        ],
        'splash' => [
            '640x1136' => '/assets/images/logo2earn.png',
            '750x1334' => '/assets/images/logo2earn.png',
            '828x1792' => '/assets/images/logo2earn.png',
            '1125x2436' => '/assets/images/logo2earn.png',
            '1242x2208' => '/assets/images/logo2earn.png',
            '1242x2688' => '/assets/images/logo2earn.png',
            '1536x2048' => '/assets/images/logo2earn.png',
            '1668x2224' => '/assets/images/logo2earn.png',
            '1668x2388' => '/assets/images/logo2earn.png',
            '2048x2732' => '/assets/images/logo2earn.png',
        ],
        'shortcuts' => [
            [
                'name' => 'Shortcut Link 1',
                'description' => 'Shortcut Link 1 Description',
                'url' => '/shortcutlink1',
                'icons' => [
                    "src" => "/images/icons/icon-72x72.png",
                    "purpose" => "any"
                ]
            ],
            [
                'name' => 'Shortcut Link 2',
                'description' => 'Shortcut Link 2 Description',
                'url' => '/shortcutlink2'
            ]
        ],
        'custom' => []
    ]
];
