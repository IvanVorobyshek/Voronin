<?php

namespace Voronin\CoinsPayment\Model;

use Magento\Framework\Model\AbstractModel;
use Voronin\CoinsPayment\Api\Data\CoinsInterface;

class Coins extends AbstractModel implements CoinsInterface
{

    /**
     * Initialize resource model
     * @return void
     */
    public function _construct()
    {
        $this->_init('Voronin\CoinsPayment\Model\ResourceModel\Coins');
    }

    public function getEntityId():int|null
    {
        return (int)$this->getData(self::ENTITY_ID);
    }

    public function setEntityId($id):CoinsInterface
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    public function getCustomerId():int|null
    {
        return (int)$this->getData(self::CUSTOMER_ID);
    }

    public function setCustomerId($customerId):CoinsInterface
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    public function getOrderId():int|null
    {
        return (int)$this->getData(self::ORDER_ID);
    }

    public function setOrderId($orderId):CoinsInterface
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    public function getIsOrderCompleted():int
    {
        return (int)$this->getData(self::IS_ORDER_COMPLETED);
    }

    public function setIsOrderCompleted(int $isCompleted):CoinsInterface
    {
        return $this->setData(self::IS_ORDER_COMPLETED, $isCompleted);
    }

    public function getAmountOfPurchase():float
    {
        return (float)$this->getData(self::AMOUNT_OF_PURCHASE);
    }

    public function setAmountOfPurchase(float $amountOfPurchase):CoinsInterface
    {
        return $this->setData(self::AMOUNT_OF_PURCHASE, $amountOfPurchase);
    }

    public function getReceivedCoins():float
    {
        return (float)$this->getData(self::COINS_RECEIVED);
    }

    public function setReceivedCoins(float $coins):CoinsInterface
    {
        return $this->setData(self::COINS_RECEIVED, $coins);
    }

    public function getSpendCoins():float
    {
        return (float)$this->getData(self::COINS_SPEND);
    }

    public function setSpendCoins(float $coins):CoinsInterface
    {
        return $this->setData(self::COINS_SPEND, $coins);
    }

    public function getDate():string
    {
        return (string)$this->getData(self::DATE);
    }

    public function setDate(string $date):CoinsInterface
    {
        return $this->setData(self::DATE, $date);
    }
}
