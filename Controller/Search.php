<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Search\Controller;

use Site\Controller\AbstractController;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Validate\Pattern;
use Krystal\Validate\Renderer\MessagesOnly as MessagesOnlyRenderer;

final class Search extends AbstractController
{
    /**
     * Handles search on site
     * 
     * @return string
     */
    public function searchAction()
    {
        // Target key which contains user's entered data
        $key = 'query';

        if ($this->request->hasQuery($key)) {
            $keyword = strip_tags($this->request->getQuery($key));

            $formValidator = $this->getValidator($this->request->getQuery());

            $this->loadPlugins();

            // It's time to grab configuration entity
            $config = $this->getConfig();

            // Get site service to define target keyword
            $siteService = $this->getModuleService('siteService');
            $siteService->setKeyword($keyword);

            if ($formValidator->isValid()) {
                $searchManager = $this->getModuleService('searchManager');
                $pageNumber = (int) $this->request->getQuery('page', 1);

                // Override maximal description's length
                $searchManager->setMaxDescriptionLength($config->getMaxDescriptionLength());
                $results = $searchManager->findByKeyword($keyword, $pageNumber, $config->getPerPageCount());

                // Template variables
                $vars = [
                    'search' => $siteService,
                    'page' => $this->getPage(),
                    'results' => $results,
                    'paginator' => $searchManager->getPaginator()
                ];

            } else {
                // Template variables when we have errors
                $vars = [
                    'search' => $siteService,
                    'page' => $this->getPage(),
                    'errors' => $formValidator->getErrors()
                ];
            }

            // Append languages
            $vars['languages'] = $this->getService('Cms', 'languageManager')->fetchAll(true);

            return $this->view->render('search', $vars);
        } else {
            // No query key in $_GET? Well, then simply trigger 404
            return false;
        }
    }

    /**
     * Loads required plugins for view
     * 
     * @return void
     */
    private function loadPlugins()
    {
        $this->loadSitePlugins();
        $this->view->getBreadcrumbBag()
                   ->addOne($this->translator->translate('Search'));
    }

    /**
     * Returns page's entity
     * 
     * @return \Krystal\Stdlib\VirtualEntity
     */
    private function getPage()
    {
        $title = $this->translator->translate('Search results');

        $entity = new VirtualEntity();
        $entity->setTitle($title)
               ->setName($title)
               ->setMetaDescription(null);

        return $entity;
    }

    /**
     * Returns configuration manager
     * 
     * @return \Krystal\Stdlib\VirtualEntity
     */
    private function getConfig()
    {
        return $this->getModuleService('configManager')->getEntity();
    }

    /**
     * Returns prepared form validator
     * 
     * @param array $input Raw query data
     * @return \Krystal\Validate\ValidatorChain
     */
    private function getValidator(array $input)
    {
        $this->validatorFactory->setRenderer(new MessagesOnlyRenderer());

        return $this->validatorFactory->build([
            'input' => [
                'source' => $input,
                'definition' => [
                    'query' => new Pattern\Query()
                ]
            ]
        ]);
    }
}
