<?php

/**
 * Module configuration container
 */

return [
    'name' => 'Search',
    'description' => 'Search module allows you to easily enable search mechanism across another modules',
    'menu' => [
        'name' => 'Search',
        'icon' => 'fas fa-search',
        'items' => [
            [
                'route' => 'Search:Admin:Config@indexAction',
                'name' => 'Configuration'
            ]
        ]
    ]
];