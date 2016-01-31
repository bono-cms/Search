<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Search\Controller\Admin;

use Cms\Controller\Admin\AbstractConfigController;
use Krystal\Validate\Pattern;

final class Config extends AbstractConfigController
{
    /**
     * {@inheritDoc}
     */
    protected function loadPlugins()
    {
        $this->view->getPluginBag()
                   ->appendScript('@Search/admin/config.js');

        // Override default breadcrumbs collection
        $this->view->getBreadcrumbBag()
                   ->addOne('Search');
    }

    /**
     * {@inheritDoc}
     */
    protected function getValidationRules()
    {
        return array(
            'per_page_count' => new Pattern\PerPageCount(),
        );
    }
}
