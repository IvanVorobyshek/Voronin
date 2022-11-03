<?php

namespace Voronin\HeightAttribute\ViewModel\Catalog\Product\Attribute;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Catalog\Model\Session as CatalogSession;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product as ProductResourceModel;
use Magento\Catalog\Block\Product\View;

class Height implements ArgumentInterface
{
    /**
     * @var int
     */
    private int $heightValue = 0;

    /**
     * @var Product
     */
    private Product $product;

    /**
     * @var ProductResourceModel
     */
    private ProductResourceModel $productResourceModel;

    /**
     * @var int
     */
    private int $prodId = 0;

    /**
     * @var View
     */
    private View $view;

    /**
     * @param Product $product
     * @param ProductResourceModel $productResourceModel
     * @param View $view
     */
    public function __construct(
        Product $product,
        ProductResourceModel $productResourceModel,
        View $view
    ) {
        $this->product = $product;
        $this->view = $view;
        $this->productResourceModel = $productResourceModel;
    }

    /**
     * Get ID of the current product
     *
     * @return int
     */
    public function getProdId():int
    {
        if (!$this->prodId) {
            $this->prodId = $this->view->getProduct()->getId();
        }
        return $this->prodId;
    }

    /**
     * Get Attribute Value
     *
     * @return int
     * @throws LocalizedException
     */
    public function getProductAttributeValue():int
    {
        if (!$this->heightValue) {
            $this->productResourceModel->load($this->product, $this->getProdId());
            $value = (int)$this->productResourceModel->getAttribute('height')->getFrontend()->getValue($this->product);
            $this->heightValue = $value;
        }
        return $this->heightValue;
    }

    /**
     * Get Yes or No to display Attribute or not
     *
     * @return int
     * @throws LocalizedException
     */
    public function getValueToDisplay():int
    {
        $this->productResourceModel->load($this->product, $this->getProdId());
        return $this->product->getData('is_height_display');
    }

    /**
     * Get Attribute Label
     *
     * @return string
     * @throws LocalizedException
     */
    public function getProductAttributeLabel():string
    {
        $this->productResourceModel->load($this->product, $this->getProdId());
        $productattributelabel = $this->productResourceModel->getAttribute('height')->getFrontend()->getLabel();
        return $productattributelabel;
    }
}
