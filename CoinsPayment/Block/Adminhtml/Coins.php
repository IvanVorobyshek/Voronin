<?php

namespace Voronin\CoinsPayment\Block\Adminhtml;

use Voronin\CoinsPayment\Model\ResourceModel\Coins\Collection;
use Magento\Backend\App\Action;

class Coins extends \Magento\Backend\Block\Template implements \Magento\Ui\Component\Layout\Tabs\TabInterface
{
    /**
     * Template
     *
     * @var string
     */
    protected $_template = 'customer/tab/coins/customer_view.phtml';

    private Collection $collection;

    private Action $action;

    private $customerId;

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

    public function getCustomerId():int
    {
        if ($this->customerId === null) {
            $this->customerId = (int)$this->action->getRequest()->getParam('id');
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
        $coins = $this->collection;
        foreach ($coins as $coin) {
            $total = $total + $coin->getReceivedCoins() - $coin->getSpendCoins();
        }
        return $total;
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Amount of coins');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Coins');
    }

    /**
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
     * @return bool
     */
    public function isHidden()
    {
        if ($this->getCustomerId()) {
            return false;
        }
        return true;
    }

    public function getContent()
    {
        return 'ASD!';
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
