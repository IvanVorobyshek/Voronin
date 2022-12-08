<?php

namespace Voronin\CoinsPayment\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Voronin\CoinsPayment\Api\CoinsRepositoryInterface;
use Voronin\CoinsPayment\Api\Data\CoinsInterface;
use Voronin\CoinsPayment\Model\Coins;
use Voronin\CoinsPayment\Model\CoinsFactory;
use Voronin\CoinsPayment\Model\ResourceModel\Coins as CoinsResource;

class CoinsRepository implements CoinsRepositoryInterface
{

    /**
     * @var CoinsResource
     */
    private CoinsResource $coinsResource;

    /**
     * @var CoinsFactory
     */
    private CoinsFactory $coinsFactory;

    /**
     * @param CoinsResource $coinsResource
     * @param CoinsFactory $coinsFactory
     */
    public function __construct(
        CoinsResource $coinsResource,
        CoinsFactory $coinsFactory
    ) {
        $this->coinsResource = $coinsResource;
        $this->coinsFactory = $coinsFactory;
    }

    /**
     * Get coin row by ID
     *
     * @param int $id
     * @return CoinsInterface
     * @throws NoSuchEntityException
     */
    public function get(int $id): CoinsInterface
    {
        $coin = $this->coinsFactory->create();
        $this->coinsResource->load($coin, $id);
        if (!$coin->getId()) {
            throw new NoSuchEntityException(__('Requested coin does not exist'));
        }
        return $coin;
    }

    /**
     * Save customer coins
     *
     * @param CoinsInterface $coin
     * @return CoinsInterface
     * @throws StateException
     */
    public function save(CoinsInterface $coin): CoinsInterface
    {
        try {
            $this->coinsResource->save($coin);
        } catch (\Exception $exception) {
            throw new StateException(__('Unable to save coins for user with ID = #%1', $coin->getId()));
        }
        return $coin;
    }

    /**
     * Delete customer coin
     *
     * @param CoinsInterface $coin
     * @return bool
     * @throws StateException
     */
    public function delete(CoinsInterface $coin): bool
    {
        try {
            $this->coinsResource->delete($coin);
        } catch (\Exception $e) {
            throw new StateException(__('Unable to remove coins for user with ID =  #%1', $coin->getId()));
        }
        return true;
    }

    /**
     * Delete Customer coin by ID
     *
     * @param int $id
     * @return bool
     * @throws NoSuchEntityException
     * @throws StateException
     */
    public function deleteById(int $id): bool
    {
        return $this->delete($this->get($id));
    }
}
