<?php

namespace Voronin\CoinsPayment\Model\Payment;

//Magento\Payment\Model\Method\Adapter
class CoinsPayment extends \Magento\Payment\Model\Method\AbstractMethod
{

    const CUSTOM_PAYMENT_CODE = 'coinspayment';

    protected $_code = self::CUSTOM_PAYMENT_CODE;

    protected $_formBlockType = \Voronin\CoinsPayment\Block\Form\Coinspayment::class;

    protected $_infoBlockType = \Magento\Payment\Block\Info\Instructions::class;

    /**
     * Availability option
     *
     * @var bool
     */
    protected $_isOffline = true;

    /**
     * Get instructions text from config
     *
     * @return string
     */
    public function getInstructions()
    {
        return trim($this->getConfigData('instructions'));
    }

}
