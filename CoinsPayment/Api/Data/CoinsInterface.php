<?php

namespace Voronin\CoinsPayment\Api\Data;

interface CoinsInterface
{
    public const ENTITY_ID = 'entity_id';
    public const CUSTOMER_ID = 'customer_id';
    public const ORDER_ID = 'order_id';
    public const IS_ORDER_COMPLETED = 'is_order_completed';
    public const AMOUNT_OF_PURCHASE = 'amount_of_purchase';
    public const COINS_RECEIVED = 'coins_received';
    public const COINS_SPEND = 'coins_spend';
    public const DATE = 'date';

    /**
     * Get Entity ID
     *
     * @return int|null
     */
    public function getEntityId():int|null;

    /**
     * Set Entity ID
     *
     * @param int $id
     * @return mixed
     */
    public function setEntityId(int $id);

    /**
     * Get Customer ID
     *
     * @return int|null
     */
    public function getCustomerId():int|null;

    /**
     * Set Customer ID
     *
     * @param int $customerId
     * @return CoinsInterface
     */
    public function setCustomerId(int $customerId):CoinsInterface;

    /**
     * Get Order ID
     *
     * @return int|null
     */
    public function getOrderId():int|null;

    /**
     * Set Order ID
     *
     * @param int $orderId
     * @return CoinsInterface
     */
    public function setOrderId(int $orderId):CoinsInterface;

    /**
     * Get data that is Order Completed or not
     *
     * @return int
     */
    public function getIsOrderCompleted():int;

    /**
     * Set Order Completed
     *
     * @param int $isOrderCompleted
     * @return CoinsInterface
     */
    public function setIsOrderCompleted(int $isOrderCompleted):CoinsInterface;

    /**
     * Get Amount of Purchase
     *
     * @return float
     */
    public function getAmountOfPurchase():float;

    /**
     * Set Amount of Purchase
     *
     * @param float $amountOfPurchase
     * @return CoinsInterface
     */
    public function setAmountOfPurchase(float $amountOfPurchase):CoinsInterface;

    /**
     * Get Received Coins
     *
     * @return float
     */
    public function getReceivedCoins():float;

    /**
     * Set Received Coins
     *
     * @param float $coins
     * @return CoinsInterface
     */
    public function setReceivedCoins(float $coins):CoinsInterface;

    /**
     * Get Spent Coins
     *
     * @return float
     */
    public function getSpendCoins():float;

    /**
     * Set Spent Coins
     *
     * @param float $coins
     * @return CoinsInterface
     */
    public function setSpendCoins(float $coins):CoinsInterface;

    /**
     * Get Date Of Purchase
     *
     * @return string
     */
    public function getDate():string;

    /**
     * Set Date Of Purchase
     *
     * @param string $date
     * @return CoinsInterface
     */
    public function setDate(string $date):CoinsInterface;
}
