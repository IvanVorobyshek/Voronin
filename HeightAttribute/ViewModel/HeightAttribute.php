<?php

namespace Voronin\HeightAttribute\ViewModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Catalog\Model\Session as CatalogSession;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product as ProductResourceModel;

class HeightAttribute implements ArgumentInterface
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
     * @var CatalogSession
     */
    private CatalogSession $catalogSession;

    /**
     * @param Product $product
     * @param ProductResourceModel $productResourceModel
     * @param CatalogSession $catalogSession
     */
    public function __construct(
        Product $product,
        ProductResourceModel $productResourceModel,
        CatalogSession $catalogSession
    ) {
        $this->product = $product;
        $this->productResourceModel = $productResourceModel;
        $this->catalogSession = $catalogSession;
    }

    /**
     * Get ID of the current product
     *
     * @return int
     */
    public function getProdId():int
    {
        if (!$this->prodId) {
            $this->prodId = (int)$this->catalogSession->getData('last_viewed_product_id');
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
            $productId = $this->getProdId();
            $this->productResourceModel->load($this->product, $productId);
            $value = (int)$this->productResourceModel->getAttribute('Height')->getFrontend()->getValue($this->product);
            $this->heightValue = $value;
        }
        return $this->heightValue;
    }

    /**
     * Get Yes or No to display Attribute or not
     *
     * @return string
     * @throws LocalizedException
     */
    public function getYesNoValue():string
    {
        $productId = $this->getProdId();
        $this->productResourceModel->load($this->product, $productId);
        return $this->productResourceModel->getAttribute('height_yes_no')->getFrontend()->getValue($this->product);
    }

    /**
     * Get Attribute Label
     *
     * @return string
     * @throws LocalizedException
     */
    public function getProductAttributeLabel():string
    {
        $productId = $this->getProdId();
        $this->productResourceModel->load($this->product, $productId);
        $productattributelabel = $this->productResourceModel->getAttribute('Height')->getFrontend()->getLabel();
        return $productattributelabel;
    }
}
