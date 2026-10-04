<?php

/**
 * This file is part of the Bono CMS
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Search;

use Cms\AbstractCmsModule;
use Search\Service\SearchManager;
use Search\Service\SiteService;

final class Module extends AbstractCmsModule
{
    /**
     * Returns mappers that should be attached to the search
     * 
     * @param array $collection
     * @return array
     */
    private function grabMappers(array $collection)
    {
        $result = [];

        foreach ($collection as $module => $mappers) {
            // Append only from loaded modules
            if ($this->moduleManager->isLoaded($module)) {
                foreach ($mappers as $mapper) {
                    array_push($result, $mapper);
                }
            }
        }

        return $result;
    }

    /**
     * Returns all attached mappers for main mapper
     * 
     * @return array
     */
    private function getAttachedMappers()
    {
        return [
            'Pages' => [
                '/Pages/Storage/MySQL/SearchMapper'
            ],
            'News' => [
                '/News/Storage/MySQL/SearchMapper'
            ],
            /*
            'Shop' => [
                '/Shop/Storage/MySQL/SearchMapper'
            ],
            */
            'Blog' => [
                '/Blog/Storage/MySQL/SearchMapper'
            ],
            'Announcement' => [
                '/Announcement/Storage/MySQL/SearchMapper'
            ]
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceProviders()
    {
        $searchMapper = $this->getMapper('/Search/Storage/MySQL/SearchMapper');

        foreach ($this->grabMappers($this->getAttachedMappers()) as $mapper) {
            $searchMapper->append($this->getMapper($mapper));
        }

        return [
            'siteService' => new SiteService(),
            'configManager' => $this->createConfigService(),
            'searchManager' => new SearchManager($searchMapper, $this->getWebPageManager())
        ];
    }
}