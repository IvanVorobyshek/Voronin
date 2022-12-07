<?php

namespace Voronin\CoinsPayment\Block\Customer;

use Magento\Customer\Model\Session;
use Voronin\CoinsPayment\Model\ResourceModel\Coins\Collection;
use Voronin\CoinsPayment\Model\ResourceModel\Coins\CollectionFactory;

class Coins extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Collection
     */
    private Collection $collection;

    private CollectionFactory $collectionFactory;

    private Session $customerSession;

    private $customerId;

    /**
     * @param Collection $collection
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

    public function getCustomerId(): int
    {
        if ($this->customerId === null) {
            $this->customerId = (int) $this->customerSession->getCustomer()->getId();
        }
        return $this->customerId;
    }

    public function getCollection(int $id): Collection
    {
        $this->collection->addFieldToSelect(['customer_id', 'order_id', 'amount_of_purchase',
            'coins_received', 'coins_spend', 'date']);
        $this->collection->addFieldToFilter('customer_id', ['eq' => $id]);
        $this->collection->addFieldToFilter('is_order_completed', ['eq' => '1']);
        return $this->collection;
    }

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

    public function getContent(): string
    {
        return 'DD!';
    }

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

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}
