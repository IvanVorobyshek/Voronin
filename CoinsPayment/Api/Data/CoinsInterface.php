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

    public function getEntityId():int|null;
    public function setEntityId(int $id);

    public function getCustomerId():int|null;
    public function setCustomerId(int $customerId):CoinsInterface;

    public function getOrderId():int|null;
    public function setOrderId(int $orderId):CoinsInterface;

    public function getIsOrderCompleted():int;
    public function setIsOrderCompleted(int $isOrderCompleted):CoinsInterface;

    public function getAmountOfPurchase():float;
    public function setAmountOfPurchase(float $amountOfPurchase):CoinsInterface;

    public function getReceivedCoins():float;
    public function setReceivedCoins(float $coins):CoinsInterface;

    public function getSpendCoins():float;
    public function setSpendCoins(float $coins):CoinsInterface;

    public function getDate():string;
    public function setDate(string $date):CoinsInterface;

}
