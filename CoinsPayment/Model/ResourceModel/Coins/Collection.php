<?php

namespace Voronin\CoinsPayment\Model\ResourceModel\Coins;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Voronin\CoinsPayment\Model\Coins', 'Voronin\CoinsPayment\Model\ResourceModel\Coins');
    }
}
