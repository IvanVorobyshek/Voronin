<?php

namespace Voronin\CoinsPayment\Observer;

use Magento\Checkout\Model\Session;
use Magento\Framework\DB\Transaction;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;
use Magento\Sales\Model\Service\InvoiceService;
use Voronin\CoinsPayment\Api\CoinsRepositoryInterface;
use Voronin\CoinsPayment\Model\CoinsFactory;
use Voronin\CoinsPayment\Model\Config;
use Voronin\CoinsPayment\ViewModel\Catalog\Product\Coins;

class CheckoutSubmitAllAfterAutoInvoiceCoinsSave implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var InvoiceService
     */
    private InvoiceService $invoiceService;

    /**
     * @var Transaction
     */
    private Transaction $transaction;

    /**
     * @var InvoiceSender
     */
    private InvoiceSender $invoiceSender;

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
     * @param InvoiceService $invoiceService
     * @param InvoiceSender $invoiceSender
     * @param Transaction $transaction
     * @param Session $session
     * @param Coins $coins
     * @param Config $config
     * @param CoinsFactory $coinsFactory
     * @param CoinsRepositoryInterface $coinsRepository
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        InvoiceService $invoiceService,
        InvoiceSender $invoiceSender,
        Transaction $transaction,
        Session $session,
        Coins $coins,
        Config $config,
        CoinsFactory $coinsFactory,
        CoinsRepositoryInterface $coinsRepository,
        ManagerInterface $messageManager
    ) {
        $this->messageManager = $messageManager;
        $this->invoiceService = $invoiceService;
        $this->transaction = $transaction;
        $this->invoiceSender = $invoiceSender;

        $this->session = $session;
        $this->coins = $coins;
        $this->coinsFactory = $coinsFactory;
        $this->coinsRepository = $coinsRepository;
        $this->config = $config;
    }

    /**
     * Save coins, make autoinvoice
     *
     * @param Observer $observer
     * @return $this|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $state = $order->getState();
        $paymentMethod = $order->getPayment()->getMethod();

        if ($paymentMethod === 'coinspayment') {//coins spent
            $this->addSpendCoins(true);
        } else {
            if ($this->config->isEnabled()) {//coins receive
                $this->addSpendCoins(false);
            }
        }

        if ($paymentMethod === "coinspayment" && $state === "new") {
            //make autoinvoice
            if ($order->canInvoice()) {
                $invoice = $this->invoiceService->prepareInvoice($order);
                $invoice->setRequestedCaptureCase(\Magento\Sales\Model\Order\Invoice::CAPTURE_OFFLINE);
                $invoice->register();
                $invoice->getOrder()->setCustomerNoteNotify(false);
                $invoice->getOrder()->setIsInProcess(true);
                $order->addStatusHistoryComment(__('Automatically invoiced'), false);
                $transactionSave =
                    $this->transaction
                        ->addObject($invoice)
                        ->addObject($invoice->getOrder());
                $transactionSave->save();
                $this->invoiceSender->send($invoice);
                $order->addCommentToStatusHistory(
                    __('Notified customer about invoice creation #%1.', $invoice->getId())
                )->setIsCustomerNotified(true)->save();
            }
        }
        return $this;
    }

    /**
     * Add a row with coins data to the table
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
        if ($customerId === 0) {
            return;
        }

        //get order id
        $orderId = $quote->getReservedOrderId();
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
