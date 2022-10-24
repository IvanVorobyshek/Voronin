<?php

namespace Voronin\Cars\Api;

use Voronin\Cars\Api\Data\CarInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Voronin\Cars\Api\Data\CarSearchResultInterface;

interface CarRepositoryInterface
{
    /**
     * Get Car by ID
     *
     * @param int $id
     * @return CarInterface
     */
    public function get(int $id): CarInterface;

    /**
     * Get List
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return CarSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): CarSearchResultInterface;

    /**
     * Save Car
     *
     * @param CarInterface $car
     * @return CarInterface
     */
    public function save(CarInterface $car): CarInterface;

    /**
     * Delete Car
     *
     * @param CarInterface $car
     * @return bool
     */
    public function delete(CarInterface $car): bool;

    /**
     * Delete Car by ID
     *
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool;
}
