<?php

namespace Voronin\CoinsPayment\Block\Customer;

use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template\Context;
use Voronin\CoinsPayment\Model\ResourceModel\Coins\Collection;
use Voronin\CoinsPayment\Model\ResourceModel\Coins\CollectionFactory;

class Coins extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Collection
     */
    private Collection $collection;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var Session
     */
    private Session $customerSession;

    /**
     * @var int
     */
    private $customerId;

    /**
     * @param Context $context
     * @param Collection $collection
     * @param CollectionFactory $collectionFactory
     * @param Session $customerSession
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        Collection $collection,
        CollectionFactory $collectionFactory,
        Session $customerSession,
        array $data = []
    ) {
        $this->collection = $collection;
        $this->collectionFactory = $collectionFactory;
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    /**
     * Get Customer ID
     *
     * @return int
     */
    public function getCustomerId(): int
    {
        if ($this->customerId === null) {
            $this->customerId = (int) $this->customerSession->getCustomer()->getId();
        }
        return $this->customerId;
    }

    /**
     * Get Customer's Coins Data
     *
     * @param int $id
     * @return Collection
     */
    public function getCollection(int $id): Collection
    {
        $this->collection->addFieldToSelect(['customer_id', 'order_id', 'amount_of_purchase',
            'coins_received', 'coins_spend', 'date']);
        $this->collection->addFieldToFilter('customer_id', ['eq' => $id]);
        $this->collection->addFieldToFilter('is_order_completed', ['eq' => '1']);
        return $this->collection;
    }

    /**
     * Get The Total Of Customer's Coins
     *
     * @param int $id
     * @return float
     */
    public function getTotalCoins(int $id): float
    {
        $total = 0;
        $coins = $this->collectionFactory->create();
        $coins->addFieldToSelect(['coins_received', 'coins_spend']);
        $coins->addFieldToFilter('customer_id', ['eq' => $id]);
        $coins->addFieldToFilter('is_order_completed', ['eq' => '1']);
        foreach ($coins as $coin) {
            $total = $total + $coin->getReceivedCoins() - $coin->getSpendCoins();
        }
        return $total;
    }

    /**
     * Prepare Layout
     *
     * @return $this|Coins
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getCollection($this->getCustomerId())) {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'sales.coins.history.pager'
            )->setCollection(
                $this->getCollection($this->getCustomerId())
            );
            $this->setChild('pager', $pager);
            $this->getCollection($this->getCustomerId())->load();
        }
        return $this;
    }

    /**
     * Get Pager
     *
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}
