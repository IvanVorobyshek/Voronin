<?php

namespace Voronin\CoinsPayment\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Message\ManagerInterface;
use Voronin\CoinsPayment\Api\CoinsRepositoryInterface;
use Voronin\CoinsPayment\Model\ResourceModel\Coins\Collection;

class SalesOrderAfterSavedObserver implements \Magento\Framework\Event\ObserverInterface
{
    private Collection $collection;

    private CoinsRepositoryInterface $coinsRepository;

    private ManagerInterface $messageManager;

    public function __construct(
        Collection $collection,
        CoinsRepositoryInterface $coinsRepository,
        ManagerInterface $messageManager
    ) {
        $this->collection = $collection;
        $this->coinsRepository = $coinsRepository;
        $this->messageManager = $messageManager;
    }
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $paymentMethod = $order->getPayment()->getMethod();
        //if payment method === coinspayment - it's already is_completed = 1
        if ($paymentMethod === "coinspayment") {
            return $this;
        }

        $state = $order->getState();
        if ($state !== "complete") {
            return $this;
        }

        $customerId = $order->getCustomerId();
        $orderId = $order->getEntityId();
        $this->collection->addFieldToSelect(['entity_id']);
        $this->collection->addFieldToFilter('customer_id', ['eq' => $customerId]);
        $this->collection->addFieldToFilter('order_id', ['eq' => $orderId]);
        $this->collection->addFieldToFilter('is_order_completed', ['neq' => '1']);
        $orders = $this->collection->load()->getData();
        //no orders to make is_completed = 1
        if (empty($orders)) {
            return $this;
        }
        $id = (int)$orders[0]['entity_id'];
        $coin = $this->coinsRepository->get($id);
        try {
            $coin->setIsOrderCompleted(1);
            $this->coinsRepository->save($coin);
            $this->messageManager->addSuccessMessage(__('Data has been successfully saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
        }

        return $this;
    }
}
