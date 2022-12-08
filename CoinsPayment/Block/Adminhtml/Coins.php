<?php

namespace Voronin\CoinsPayment\Block\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Phrase;
use Voronin\CoinsPayment\Model\ResourceModel\Coins\Collection;

class Coins extends \Magento\Backend\Block\Template implements \Magento\Ui\Component\Layout\Tabs\TabInterface
{
    /**
     * Template File
     *
     * @var string
     */
    protected $_template = 'customer/tab/coins/customer_view.phtml';

    /**
     * @var Collection
     */
    private Collection $collection;

    /**
     * @var Action
     */
    private Action $action;

    /**
     * @var int
     */
    private $customerId;

    /**
     * @param Context $context
     * @param Action $action
     * @param Collection $collection
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        Action $action,
        Collection $collection,
        array $data = []
    ) {
        $this->collection = $collection;
        $this->action = $action;
        parent::__construct($context, $data);
    }

    /**
     * Get Customer ID
     *
     * @return int
     */
    public function getCustomerId():int
    {
        if ($this->customerId === null) {
            $this->customerId = (int)$this->action->getRequest()->getParam('id');
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
        $coins = $this->collection;
        foreach ($coins as $coin) {
            $total = $total + $coin->getReceivedCoins() - $coin->getSpendCoins();
        }
        return $total;
    }

    /**
     * Get Tab Label
     *
     * @return Phrase|string
     */
    public function getTabLabel()
    {
        return __('Amount of coins');
    }

    /**
     * Get Tab Title
     *
     * @return Phrase|string
     */
    public function getTabTitle()
    {
        return __('Coins');
    }

    /**
     * To Show Tab or not
     *
     * @return bool
     */
    public function canShowTab()
    {
        if ($this->getCustomerId()) {
            return true;
        }
        return false;
    }

    /**
     * Is Tab hidden
     *
     * @return bool
     */
    public function isHidden()
    {
        if ($this->getCustomerId()) {
            return false;
        }
        return true;
    }

    /**
     * Tab class getter
     *
     * @return string
     */
    public function getTabClass()
    {
        return '';
    }

    /**
     * Return URL link to Tab content
     *
     * @return string
     */
    public function getTabUrl()
    {
        return '';
    }

    /**
     * Tab should be loaded trough Ajax call
     *
     * @return bool
     */
    public function isAjaxLoaded()
    {
        return false;
    }
}
