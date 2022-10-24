<?php

namespace Voronin\Cars\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Voronin\Cars\Model\ResourceModel\Cars\Collection;

class Cars implements ArgumentInterface
{
    /**
     * @var Collection
     */
    private Collection $collection;

    /**
     * @param Collection $collection
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Get All Cars to display
     *
     * @return Collection
     */
    public function getAllCars(): Collection
    {
        return $this->collection;
    }
}
