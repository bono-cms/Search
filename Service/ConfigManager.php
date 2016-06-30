<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Search\Service;

use Krystal\Config\ConfigModuleService;
use Krystal\Stdlib\VirtualEntity;

final class ConfigManager extends ConfigModuleService
{
    /**
     * {@inheritDoc}
     */
    public function getEntity()
    {
        $entity = new VirtualEntity;
        $entity->setPerPageCount($this->get('per_page_count', 5), VirtualEntity::FILTER_INT)
               ->setMaxDescriptionLength($this->get('max_description_length', 100), VirtualEntity::FILTER_INT);

        return $entity;
    }
}
