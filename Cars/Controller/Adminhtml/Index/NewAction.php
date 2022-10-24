<?php

namespace Voronin\Cars\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Voronin\Cars\Model\CarsFactory;

class NewAction extends Action
{
    /**
     * @var PageFactory
     */
    private PageFactory $pageFactory;

    /**
     * @var CarsFactory
     */
    protected CarsFactory $carsFactory;

    /**
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param CarsFactory $carsFactory
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        CarsFactory $carsFactory
    ) {
        $this->pageFactory = $pageFactory;
        $this->carsFactory = $carsFactory;
        parent::__construct($context);
    }

    /**
     * Add a page with form to add new car
     *
     * @return Page
     */
    public function execute(): Page
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('Magento_Catalog::catalog_products');
        $title = __('Add Car');
        $resultPage->getConfig()->getTitle()->prepend($title);
        return $resultPage;
    }

    /**
     * Check user's rights
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Voronin_Cars::add');
    }
}
