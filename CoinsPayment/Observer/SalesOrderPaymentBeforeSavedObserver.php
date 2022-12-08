<?php

namespace Voronin\CoinsPayment\Observer;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Webapi\Controller\Rest\InputParamsResolver;
use Voronin\CoinsPayment\Api\CoinsRepositoryInterface;
use Voronin\CoinsPayment\Model\CoinsFactory;
use Voronin\CoinsPayment\Model\Config;
use Voronin\CoinsPayment\ViewModel\Catalog\Product\Coins;

class SalesOrderPaymentBeforeSavedObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var InputParamsResolver
     */
    protected $inputParamsResolver;

    /**
     * @var RequestInterface
     */
    protected $requestInterface;

    /**
     * @var Session
     */
    private Session $session;

    /**
     * @var Coins
     */
    private Coins $coins;

    /**
     * @var CoinsFactory
     */
    private CoinsFactory $coinsFactory;

    /**
     * @var CoinsRepositoryInterface
     */
    private CoinsRepositoryInterface $coinsRepository;

    /**
     * @var Config
     */
    private Config $config;

    /**
     * @var ManagerInterface
     */
    private ManagerInterface $messageManager;

    /**
     * @param InputParamsResolver $inputParamsResolver
     * @param RequestInterface $requestInterface
     * @param Session $session
     * @param Coins $coins
     * @param Config $config
     * @param CoinsFactory $coinsFactory
     * @param ManagerInterface $messageManager
     * @param CoinsRepositoryInterface $coinsRepository
     */
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

    /**
     * Add or Take Coins
     *
     * @param Observer $observer
     * @return $this|void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
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

    /**
     * Pay with money or coins
     *
     * @param bool $coinsPayment
     * @return void
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
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
