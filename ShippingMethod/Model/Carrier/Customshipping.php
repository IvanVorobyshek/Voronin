<?php

namespace Voronin\ShippingMethod\Model\Carrier;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Quote\Model\QuoteRepository;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\ResultFactory;
use Psr\Log\LoggerInterface;

/**
 * Custom shipping model
 */
class Customshipping extends AbstractCarrier implements CarrierInterface
{
    public const COUNTRYRATE = ['US' => 3, 'CA' => 7, 'UA' => 2];

    /**
     * @var string
     */
    protected $_code = 'customshipping';

    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    private $rateResultFactory;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    private $rateMethodFactory;

    /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    private $quoteRep;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory $rateErrorFactory
     * @param LoggerInterface $logger
     * @param ResultFactory $rateResultFactory
     * @param MethodFactory $rateMethodFactory
     * @param QuoteRepository $quoteRep
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        \Magento\Quote\Model\QuoteRepository $quoteRep,
        array $data = []
    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->quoteRep = $quoteRep;
    }

    /**
     * Get allowed shipping methods names
     *
     * @return array
     */
    public function getAllowedMethods()
    {
        return [$this->_code => $this->getConfigData('name')];
    }

    /**
     * Custom Shipping Rates Collector
     *
     * @param RateRequest $request
     * @return \Magento\Shipping\Model\Rate\Result|bool
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->rateResultFactory->create();

        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->rateMethodFactory->create();

        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod($this->_code);
        $method->setMethodTitle($this->getConfigData('name'));

        // get the quote ID
        $req = $request->getAllItems();
        $quoteId = (int)$req[0]->getData('quote_id');
        // get quote
        $quote = $this->quoteRep->get($quoteId);
        // Base_cart_total
        $totalPrice = $quote->getBaseSubtotal();
        // Custom_shipping_fee
        $shippingCost = (float)$this->getConfigData('shipping_cost');
        // Country_rate
        $cid = $request->getDestCountryId();
        $countryRate = self::COUNTRYRATE[$cid] ?? 1;
        // Number_of_total_products_in_card
        $totalQty = $quote->getItemsQty();
        if ($totalQty <= 0) {
            return false;
        }
        // Number_of_uniq_products_in_card
        $uniqueQty = $quote->getItemsCount();
        // Calculating the shipping cost
        $shippingCost = ($totalPrice * $shippingCost * $uniqueQty) / $totalQty * $countryRate;

        $method->setPrice($shippingCost);
        $method->setCost($shippingCost);

        $result->append($method);

        return $result;
    }
}
