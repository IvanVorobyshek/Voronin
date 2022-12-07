<?php

namespace Voronin\CoinsPayment\Model\Payment;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Escaper;
use Magento\Payment\Helper\Data as PaymentHelper;
use Voronin\CoinsPayment\Block\Customer\Coins;
use Voronin\CoinsPayment\Model\Config;

class PaymentConfigProvider implements ConfigProviderInterface
{

    const CODE = 'coinspayment';

    private Coins $coins;

    private Config $config;

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
        Config $config,
        Coins $coins
    ) {
        $this->coins = $coins;
        $this->escaper = $escaper;
        $this->config = $config;
        $this->methods['coinspayment'] = $paymentHelper->getMethodInstance('coinspayment');
    }

    public function getConfig()
    {
        $config = [];
        $paymentInstruction = $this->config->getCoinsPaymentInstructions();
        $config['payment']['instructions']['coinspayment'] = nl2br($this->escaper->escapeHtml($paymentInstruction));

        $config['payment']['instructions']['coins'] = $this->coins->getTotalCoins($this->coins->getCustomerId());
        return $config;
    }
}
