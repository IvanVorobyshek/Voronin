<?php

namespace Voronin\CoinsPayment\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Coins extends AbstractDb
{

    public const TABLE_NAME = 'voronin_coins_payment';

    public const ID_FIELD_NAME = 'entity_id';

    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(self::TABLE_NAME, self::ID_FIELD_NAME);
    }


}

