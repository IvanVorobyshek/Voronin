<?php

namespace Voronin\CoinsPayment\Observer;

use Magento\Checkout\Model\Session;
use Magento\Framework\Event\ObserverInterface;

class CoinsPaymentMethodDisable implements ObserverInterface
{
    private Session $session;

    public function __construct(
        Session $session
    ) {
        $this->session = $session;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customerId = (int)$this->session->getQuote()->getCustomerId();
        if ($customerId === 0) {
            if ($observer->getEvent()->getMethodInstance()->getCode() == "coinspayment") {
                $checkResult = $observer->getEvent()->getResult();
                $checkResult->setData('is_available', false);
            }
        }
    }
}
