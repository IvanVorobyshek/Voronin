<?php

namespace Voronin\CoinsPayment\Observer;

use Magento\Checkout\Model\Session;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class CoinsPaymentMethodDisable implements ObserverInterface
{
    /**
     * @var Session
     */
    private Session $session;

    /**
     * @param Session $session
     */
    public function __construct(
        Session $session
    ) {
        $this->session = $session;
    }

    /**
     * Disable Coins Payment Method if the user in not registered
     *
     * @param Observer $observer
     * @return void
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customerId = (int)$this->session->getQuote()->getCustomerId();
        if ($customerId === 0) {
            if ($observer->getEvent()->getMethodInstance()->getCode() === "coinspayment") {
                $checkResult = $observer->getEvent()->getResult();
                $checkResult->setData('is_available', false);
            }
        }
    }
}
