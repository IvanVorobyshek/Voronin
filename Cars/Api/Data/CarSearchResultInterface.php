<?php

namespace Voronin\Cars\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface CarSearchResultInterface extends SearchResultsInterface
{
    /**
     * Get Items
     *
     * @return \Voronin\Cars\Api\Data\CarInterface[]
     */
    public function getItems();

    /**
     * Set Items
     *
     * @param \Voronin\Cars\Api\Data\CarInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
