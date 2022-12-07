<?php

namespace Voronin\CoinsPayment\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    public const XML_PATH_ENABLED = 'Voronin_Loyalty/Loyalty_Module/Loyalty_Is_Enabled';

    public const XML_PATH_SHOW_COINS_PDP_ENABLED = 'Voronin_Loyalty/Loyalty_Module/Show_Coins_PDP';

    public const XML_PATH_PERCENT_TO_COINS = 'Voronin_Loyalty/Loyalty_Module/Percent_To_Coins';

    public const XML_PATH_COINSPAYMENT_INSTRUCTIONS = 'payment/coinspayment/instructions';

    /**
     * @var ScopeConfigInterface
     */
    private $config;

    /**
     * @param ScopeConfigInterface $config
     */
    public function __construct(ScopeConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * Checks CoinsPayment module enable or disable
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->config->isSetFlag(self::XML_PATH_ENABLED);
    }

    public function isMessageToShow(): bool
    {
        return $this->config->isSetFlag(self::XML_PATH_SHOW_COINS_PDP_ENABLED);
    }

    public function getPercentValue(): float
    {
        return $this->config->getValue(self::XML_PATH_PERCENT_TO_COINS);
    }

    public function getCoinsPaymentInstructions(): string
    {
        return $this->config->getValue(self::XML_PATH_COINSPAYMENT_INSTRUCTIONS);
    }
}
