<?php

namespace Voronin\CoinsPayment\Model;

use Magento\Framework\Model\AbstractModel;
use Voronin\CoinsPayment\Api\Data\CoinsInterface;
use Voronin\CoinsPayment\Model\ResourceModel\Coins as CoinsResource;

class Coins extends AbstractModel implements CoinsInterface
{

    /**
     * Initialize resource model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(CoinsResource::class);
    }

    /**
     * Get Entity ID
     *
     * @return int|null
     */
    public function getEntityId():int|null
    {
        return (int)$this->getData(self::ENTITY_ID);
    }

    /**
     * Set Entity ID
     *
     * @param int $id
     * @return CoinsInterface
     */
    public function setEntityId($id):CoinsInterface
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    /**
     * Get Customer ID
     *
     * @return int|null
     */
    public function getCustomerId():int|null
    {
        return (int)$this->getData(self::CUSTOMER_ID);
    }

    /**
     * Set Customer ID
     *
     * @param int $customerId
     * @return CoinsInterface
     */
    public function setCustomerId(int $customerId):CoinsInterface
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * Get Order ID
     *
     * @return int|null
     */
    public function getOrderId():int|null
    {
        return (int)$this->getData(self::ORDER_ID);
    }

    /**
     * Set Order ID
     *
     * @param int $orderId
     * @return CoinsInterface
     */
    public function setOrderId(int $orderId):CoinsInterface
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    /**
     * Get data that is Order Completed or not
     *
     * @return int
     */
    public function getIsOrderCompleted():int
    {
        return (int)$this->getData(self::IS_ORDER_COMPLETED);
    }

    /**
     * Set data that is Order Completed or not
     *
     * @param int $isCompleted
     * @return CoinsInterface
     */
    public function setIsOrderCompleted(int $isCompleted):CoinsInterface
    {
        return $this->setData(self::IS_ORDER_COMPLETED, $isCompleted);
    }

    /**
     * Get Amount of Purchase
     *
     * @return float
     */
    public function getAmountOfPurchase():float
    {
        return (float)$this->getData(self::AMOUNT_OF_PURCHASE);
    }

    /**
     * Set Amount of Purchase
     *
     * @param float $amountOfPurchase
     * @return CoinsInterface
     */
    public function setAmountOfPurchase(float $amountOfPurchase):CoinsInterface
    {
        return $this->setData(self::AMOUNT_OF_PURCHASE, $amountOfPurchase);
    }

    /**
     * Get Received Coins
     *
     * @return float
     */
    public function getReceivedCoins():float
    {
        return (float)$this->getData(self::COINS_RECEIVED);
    }

    /**
     * Set Received Coins
     *
     * @param float $coins
     * @return CoinsInterface
     */
    public function setReceivedCoins(float $coins):CoinsInterface
    {
        return $this->setData(self::COINS_RECEIVED, $coins);
    }

    /**
     * Get Spent Coins
     *
     * @return float
     */
    public function getSpendCoins():float
    {
        return (float)$this->getData(self::COINS_SPEND);
    }

    /**
     * Set Spent Coins
     *
     * @param float $coins
     * @return CoinsInterface
     */
    public function setSpendCoins(float $coins):CoinsInterface
    {
        return $this->setData(self::COINS_SPEND, $coins);
    }

    /**
     * Get Date Of Purchase
     *
     * @return string
     */
    public function getDate():string
    {
        return (string)$this->getData(self::DATE);
    }

    /**
     * Set Date Of Purchase
     *
     * @param string $date
     * @return CoinsInterface
     */
    public function setDate(string $date):CoinsInterface
    {
        return $this->setData(self::DATE, $date);
    }
}
