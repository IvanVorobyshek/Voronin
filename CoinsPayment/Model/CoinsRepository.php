<?php

namespace Voronin\CoinsPayment\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Voronin\CoinsPayment\Api\CoinsRepositoryInterface;
use Voronin\CoinsPayment\Api\Data\CoinsInterface;
use Voronin\CoinsPayment\Model\Coins;
use Voronin\CoinsPayment\Model\CoinsFactory;
use Voronin\CoinsPayment\Model\ResourceModel\Coins as CoinsResource;
use Voronin\CoinsPayment\Model\ResourceModel\Coins\Collection as CoinsCollection;
use Voronin\CoinsPayment\Model\ResourceModel\Coins\CollectionFactory as CoinsCollectionFactory;

class CoinsRepository implements CoinsRepositoryInterface
{

    private $coinsResource;

    private $coinsFactory;

    private $coinsCollectionFactory;

    private $coinsSearchResultFactory;

    public function __construct(
        CoinsResource $coinsResource,
        CoinsFactory $coinsFactory,
        CoinsCollectionFactory $coinsCollectionFactory
    ) {
        $this->coinsResource = $coinsResource;
        $this->coinsFactory = $coinsFactory;
        $this->coinsCollectionFactory = $coinsCollectionFactory;
    }

    public function get(int $id): CoinsInterface
    {
        $coin = $this->coinsFactory->create();
        $this->coinsResource->load($coin, $id);
        if (!$coin->getId()) {
            throw new NoSuchEntityException(__('Requested coin does not exist'));
        }
        return $coin;
    }

    public function save(CoinsInterface $coin): CoinsInterface
    {
        try {
            $this->coinsResource->save($coin);
        } catch (\Exception $exception) {
            throw new StateException(__('Unable to save coins for user with ID = #%1', $coin->getId()));
        }
        return $coin;
    }

    public function delete(CoinsInterface $coin): bool
    {
        try {
            $this->coinsResource->delete($coin);
        } catch (\Exception $e) {
            throw new StateException(__('Unable to remove coins for user with ID =  #%1', $coin->getId()));
        }
        return true;
    }

    public function deleteById(int $id): bool
    {
        return $this->delete($this->get($id));
    }
}
