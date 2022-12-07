<?php

namespace Voronin\CoinsPayment\Observer;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Message\ManagerInterface;
use Magento\Webapi\Controller\Rest\InputParamsResolver;
use Voronin\CoinsPayment\Api\CoinsRepositoryInterface;
use Voronin\CoinsPayment\Model\CoinsFactory;
use Voronin\CoinsPayment\Model\Config;
use Voronin\CoinsPayment\ViewModel\Catalog\Product\Coins;

class SalesOrderPaymentBeforeSavedObserver implements \Magento\Framework\Event\ObserverInterface
{
    protected $inputParamsResolver;

    protected $requestInterface;

    private Session $session;

    private Coins $coins;

    private CoinsFactory $coinsFactory;

    private CoinsRepositoryInterface $coinsRepository;

    private Config $config;

    private ManagerInterface $messageManager;

    public function __construct(
        InputParamsResolver $inputParamsResolver,
        RequestInterface $requestInterface,
        Session $session,
        Coins $coins,
        Config $config,
        CoinsFactory $coinsFactory,
        ManagerInterface $messageManager,
        CoinsRepositoryInterface $coinsRepository
    ) {
        $this->inputParamsResolver = $inputParamsResolver;
        $this->requestInterface = $requestInterface;
        $this->session = $session;
        $this->coins = $coins;
        $this->coinsFactory = $coinsFactory;
        $this->coinsRepository = $coinsRepository;
        $this->config = $config;
        $this->messageManager = $messageManager;
    }

    public function execute(Observer $observer)
    {
        $payment = $observer->getEvent()->getPayment();
        if (empty($payment)) {
            return $this;
        }
        //check, if this is the first saving of payment
        $first = $this->session->getQuote()->getBaseSubtotal();
        if ($first === null) {
            return $this;
        }

        //add data
        if ($payment->getMethod() === 'coinspayment') {
            $this->addSpendCoins(true);
        } else {
            if ($this->config->isEnabled()) {
                $this->addSpendCoins(false);
            }
        }
        return $this;
    }

    public function addSpendCoins(bool $coinsPayment)
    {
        // add new coins row into table
        $coin = $this->coinsFactory->create();
        // get quote
        $quote = $this->session->getQuote();

        //get customer id
        $customerId = (int)$quote->getCustomerId();
        //get order id
        $orderId = $quote->getReservedOrderId();
        //        if ($totalPrice < 0) return $this;
        //        if (empty($customerId) or $customerId == '') return $this;
        //        if (empty($orderId) or $orderId == '') return $this;
        //we spend or earn coins
        if ($coinsPayment) {//pay with coins for purchase and shipment
            // Base_cart_total
            $totalPrice = $quote->getBaseGrandTotal();
            $coin->setSpendCoins($totalPrice);
            $coin->setIsOrderCompleted(1);
        } else {//pay with money and get coins
            // Base_cart_total
            $totalPrice = $quote->getBaseSubtotal();
            //get percent of purchase
            $coins = $this->coins->calcPercent($totalPrice);
            $coin->setReceivedCoins($coins);
            //make 1 when order is completed
            $coin->setIsOrderCompleted(0);
        }
        $coin->setCustomerId($customerId);
        $coin->setOrderId($orderId);
        $coin->setAmountOfPurchase($totalPrice);
        try {
            $this->coinsRepository->save($coin);
            $this->messageManager->addSuccessMessage(__('Coins data has been successfully saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
        }
    }
}
