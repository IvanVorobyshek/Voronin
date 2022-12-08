<?php

namespace Voronin\CoinsPayment\Model\Payment;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\LocalizedException;
use Voronin\CoinsPayment\Block\Customer\Coins;
use Voronin\CoinsPayment\Model\Config;

class PaymentConfigProvider implements ConfigProviderInterface
{

    public const CODE = 'coinspayment';

    /**
     * @var Coins
     */
    private Coins $coins;

    /**
     * @var Config
     */
    private Config $config;

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * @param Escaper $escaper
     * @param Config $config
     * @param Coins $coins
     * @throws LocalizedException
     */
    public function __construct(
        Escaper       $escaper,
        Config $config,
        Coins $coins
    ) {
        $this->coins = $coins;
        $this->escaper = $escaper;
        $this->config = $config;
    }

    /**
     * Get Config
     *
     * @return array
     */
    public function getConfig()
    {
        $config = [];
        $paymentInstruction = $this->config->getCoinsPaymentInstructions();
        $config['payment']['instructions']['coinspayment'] = nl2br($this->escaper->escapeHtml($paymentInstruction));
        $config['payment']['instructions']['coins'] = $this->coins->getTotalCoins($this->coins->getCustomerId());
        return $config;
    }
}
