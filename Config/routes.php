<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

return [
    '/%s/module/search' => [
        'controller' => 'Admin:Config@indexAction'
    ],

    '/%s/module/search/save.ajax' => [
        'controller' => 'Admin:Config@saveAction',
        'disallow' => ['guest']
    ],

    // Site route itself
    '/search' => [
        'controller' => 'Search@searchAction'
    ]
];