<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Search\Storage;

use Cms\Storage\MySQL\AbstractMapper;

interface SearchMapperInterface
{
    /**
     * Appends a searchable mapper
     * 
     * @param \Cms\Storage\MySQL\AbstractMapper
     * @return void
     */
    public function append(AbstractMapper $mapper);

    /**
     * Queries by a keyword in all attached mappers
     * 
     * @param string $keyword
     * @param integer $page Current page
     * @param integer $itemsPerPage Per page count
     * @return array
     */
    public function queryAll($keyword, $page, $itemsPerPage);
}
