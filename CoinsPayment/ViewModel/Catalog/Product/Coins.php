<?php

namespace Voronin\CoinsPayment\ViewModel\Catalog\Product;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Catalog\Block\Product\View;
use Voronin\CoinsPayment\Model\Config;

class Coins implements ArgumentInterface
{
    /**
     * @var View
     */
    private View $view;

    /**
     * @var Config
     */
    private Config $config;

    /**
     * @param View $view
     * @param Config $config
     */
    public function __construct(
        View $view,
        Config $config
    ) {
        $this->view = $view;
        $this->config = $config;
    }

    /**
     * Message with percent of product purchase
     *
     * @return string|null
     */
    public function showPercentOfPurchase(): string|null
    {
        if ($this->config->isMessageToShow()) {
            return __('You will receive %1 coins for purchasing this product', $this->getPercentOfPurchase());
        }
        return null;
    }

    /**
     * Get Percent Of Product Purchase
     *
     * @return float
     */
    public function getPercentOfPurchase(): float
    {
        //get product final price
        $price = $this->view->getProduct()->getPriceInfo()->getPrice('final_price')->getValue();
        return $this->calcPercent($price);
    }

    /**
     * Calculating Percent Of Product Purchase
     *
     * @param float $price
     * @return float
     */
    public function calcPercent(float $price): float
    {
        //get percent from settings
        $percent = $this->config->getPercentValue();
        //get the number of coins for product
        return round($price * $percent / 100.0, 1);
    }
}
