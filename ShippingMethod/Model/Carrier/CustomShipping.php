<?php

namespace Voronin\ShippingMethod\Model\Carrier;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\ResultFactory;
use Psr\Log\LoggerInterface;

/**
 * Custom shipping model
 */
class CustomShipping extends AbstractCarrier implements CarrierInterface
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
     * @var Session
     */
    private Session $session;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory $rateErrorFactory
     * @param LoggerInterface $logger
     * @param ResultFactory $rateResultFactory
     * @param MethodFactory $rateMethodFactory
     * @param Session $session
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        Session $session,
        array $data = []
    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->session = $session;
    }

    /**
     * Get allowed shipping methods names
     *
     * @return array
     */
    public function getAllowedMethods():array
    {
        return [$this->_code => $this->getConfigData('name')];
    }

    /**
     * Calculates Shipping Cost
     *
     * @param string $cid
     * @return float
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function shippingCostCalc(string $cid):float
    {
        // get quote
        $quote = $this->session->getQuote();
        // Base_cart_total
        $totalPrice = $quote->getBaseSubtotal();
        // Custom_shipping_fee
        $shippingCost = (float)$this->getConfigData('shipping_cost');
        // Country_rate
        $countryRate = self::COUNTRYRATE[$cid] ?? 1;
        // Number_of_total_products_in_card
        $totalQty = $quote->getItemsQty();
        // Number_of_uniq_products_in_card
        $uniqueQty = $quote->getItemsCount();
        // Calculating the shipping cost
        return ($totalPrice * $shippingCost * $uniqueQty) / $totalQty * $countryRate;
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

        //get ShippingCost
        $shippingCost = $this->shippingCostCalc($request->getDestCountryId());

        $method->setPrice($shippingCost);
        $method->setCost($shippingCost);

        $result->append($method);

        return $result;
    }
}
