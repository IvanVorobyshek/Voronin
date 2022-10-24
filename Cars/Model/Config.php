<?php

namespace Voronin\Cars\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    public const XML_PATH_ENABLED = 'Voronin_Cars_General/Voronin_Cars_Module/Voronin_Cars_Is_Enabled';

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
     * Checks Car module enable or disable
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->config->isSetFlag(self::XML_PATH_ENABLED);
    }
}
