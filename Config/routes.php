<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

return array(
    
    '/%s/module/search' => array(
        'controller' => 'Admin:Config@indexAction'
    ),

    '/%s/module/search/save.ajax' => array(
        'controller' => 'Admin:Config@saveAction',
        'disallow' => array('guest')
    ),
    
    // Site route itself
    '/search/(:var)' => array(
        'controller' => 'Search@searchAction'
    )
);