<?php

namespace Voronin\CoinsPayment\ViewModel\Catalog\Product;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Catalog\Block\Product\View;
use Voronin\CoinsPayment\Model\Config;


class Coins implements ArgumentInterface
{
    private View $view;

    private Config $config;

    public function __construct(
        View $view,
        Config $config
    ) {
        $this->view = $view;
        $this->config = $config;
    }

    public function showPercentOfPurchase(): string|null
    {
        if ($this->config->isMessageToShow()) {
            return __('You will receive %1 coins for purchasing this product', $this->getPercentOfPurchase());
        }
        return null;
    }

    public function getPercentOfPurchase(): float
    {
        //get product final price
        $price = $this->view->getProduct()->getPriceInfo()->getPrice('final_price')->getValue();
        return $this->calcPercent($price);
    }

    public function calcPercent(float $price): float
    {
        //get percent from settings
        $percent = $this->config->getPercentValue();
        //get the number of coins for product
        return round($price * $percent / 100.0, 1);
    }
}
