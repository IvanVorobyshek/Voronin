<?php

namespace Voronin\Cars\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Voronin\Cars\Api\CarRepositoryInterface;
use Voronin\Cars\Api\Data\CarInterface;
use Voronin\Cars\Api\Data\CarSearchResultInterface;
use Voronin\Cars\Api\Data\CarSearchResultInterfaceFactory;
use Voronin\Cars\Model\CarsFactory;
use Voronin\Cars\Model\ResourceModel\Cars as CarsResource;
use Voronin\Cars\Model\ResourceModel\Cars\Collection as CarsCollection;
use Voronin\Cars\Model\ResourceModel\Cars\CollectionFactory as CarsCollectionFactory;

class CarsRepository implements CarRepositoryInterface
{
    /**
     * @var CarsResource
     */
    private $carsResource;

    /**
     * @var CarsFactory
     */
    private $carsFactory;

    /**
     * @var CarsCollectionFactory
     */
    private $carsCollectionFactory;

    /**
     * @var CarSearchResultInterfaceFactory
     */
    private $carsSearchResultFactory;

    /**
     * @param CarsResource $carsResource
     * @param CarsFactory $carsFactory
     * @param CarsCollectionFactory $carsCollectionFactory
     * @param CarSearchResultInterfaceFactory $carsSearchResultFactory
     */
    public function __construct(
        CarsResource $carsResource,
        CarsFactory $carsFactory,
        CarsCollectionFactory $carsCollectionFactory,
        CarSearchResultInterfaceFactory $carsSearchResultFactory
    ) {
        $this->carsResource = $carsResource;
        $this->carsFactory = $carsFactory;
        $this->carsCollectionFactory = $carsCollectionFactory;
        $this->carsSearchResultFactory = $carsSearchResultFactory;
    }

    /**
     * Get Car
     *
     * @param int $id
     * @return CarInterface
     * @throws NoSuchEntityException
     */
    public function get(int $id): CarInterface
    {
        $car = $this->carsFactory->create();
        $this->carsResource->load($car, $id);
        if (!$car->getId()) {
            throw new NoSuchEntityException(__('Requested car does not exist'));
        }
        return $car;
    }

    /**
     * Get List
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Voronin\Cars\Api\Data\CarSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): CarSearchResultInterface
    {
        /** @var CarsCollection $collection */
        $collection = $this->carsCollectionFactory->create();
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }

        /** @var CarSearchResultInterface $searchResult */
        $searchResult = $this->carsSearchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        return $searchResult;
    }

    /**
     * Save Car
     *
     * @param \Voronin\Cars\Api\Data\CarInterface $car
     * @return CarInterface
     * @throws StateException
     */
    public function save(CarInterface $car): CarInterface
    {
        try {
            /** @var Cars $car */
            $this->carsResource->save($car);
        } catch (\Exception $exception) {
            throw new StateException(__('Unable to save car #%1', $car->getId()));
        }
        return $car;
    }

    /**
     * Delete Car
     *
     * @param \Voronin\Cars\Api\Data\CarInterface $car
     * @return bool
     * @throws StateException
     */
    public function delete(CarInterface $car): bool
    {
        try {
            /** @var Cars $car */
            $this->carsResource->delete($car);
        } catch (\Exception $e) {
            throw new StateException(__('Unable to remove car #%1', $car->getId()));
        }
        return true;
    }

    /**
     * Delete Car by ID
     *
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool
    {
        return $this->delete($this->get($id));
    }
}
