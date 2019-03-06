<?php

/**
 * Module configuration container
 */

return array(
    'name' => 'Search',
    'description' => 'Search module allows you to easily enable search mechanism across another modules',
    'menu' => array(
        'name' => 'Search',
        'icon' => 'fas fa-search',
        'items' => array(
            array(
                'route' => 'Search:Admin:Config@indexAction',
                'name' => 'Configuration'
            )
        )
    )
);