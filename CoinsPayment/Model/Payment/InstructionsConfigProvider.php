<?php

namespace Voronin\CoinsPayment\Model\Payment;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Escaper;
use Magento\Payment\Helper\Data as PaymentHelper;
use Voronin\CoinsPayment\Block\Customer\Coins;

class InstructionsConfigProvider implements ConfigProviderInterface
{
    // /**
    //  * @var string[]
    //  */
    // protected $methodCodes = [
    //     CoinsPayment::CUSTOM_PAYMENT_CODE,
    // ];

    private Coins $coins;

    protected $methods = [];

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * @param PaymentHelper $paymentHelper
     * @param Escaper $escaper
     */
    public function __construct(
        PaymentHelper $paymentHelper,
        Escaper       $escaper,
        Coins $coins
    )
    {
        $this->coins = $coins;
        $this->escaper = $escaper;
        $this->methods['coinspayment'] = $paymentHelper->getMethodInstance('coinspayment');
    }

    public function getConfig()
    {
        $config = [];
        $config['payment']['instructions']['coinspayment'] = nl2br($this->escaper->escapeHtml($this->methods['coinspayment']->getInstructions()));
        $config['payment']['instructions']['coins'] = $this->coins->getTotalCoins($this->coins->getCustomerId());
        return $config;
    }

    // /**
    //  * Get instructions text from config
    //  *
    //  * @param string $code
    //  * @return string
    //  */
    // protected function getInstructions($code)
    // {
    //     return nl2br($this->escaper->escapeHtml($this->methods[$code]->getInstructions()));
    // }
}
