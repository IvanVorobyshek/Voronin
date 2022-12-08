<?php

namespace Voronin\CoinsPayment\Model\ResourceModel\Coins;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Voronin\CoinsPayment\Model\Coins;
use Voronin\CoinsPayment\Model\ResourceModel\Coins as CoinsResource;

class Collection extends AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(Coins::Class, CoinsResource::Class);
    }
}
