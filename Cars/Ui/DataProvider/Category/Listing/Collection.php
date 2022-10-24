<?php

namespace Voronin\Cars\Ui\DataProvider\Category\Listing;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class Collection extends SearchResult
{
    /**
     * Override _initSelect to add custom columns
     *
     * @return void
     */
    protected function _initSelect()
    {
        $this->addFilterToMap('car_id', 'main_table.car_id');
        parent::_initSelect();
    }
}
