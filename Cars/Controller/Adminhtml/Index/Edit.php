<?php

namespace Voronin\Cars\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Voronin\Cars\Model\CarsFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Voronin\Cars\Api\CarRepositoryInterface;

class Edit extends Action
{
    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * @var CarsFactory
     */
    protected $carsFactory;

    /**
     * @var CarRepositoryInterface
     */
    private CarRepositoryInterface $carsRepository;

    /**
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param CarsFactory $carsFactory
     * @param CarRepositoryInterface $carsRepository
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        CarsFactory $carsFactory,
        CarRepositoryInterface $carsRepository
    ) {
        $this->pageFactory = $pageFactory;
        $this->carsFactory = $carsFactory;
        $this->carsRepository = $carsRepository;
        parent::__construct($context);
    }

    /**
     * Add page with data editing
     *
     * @return Page
     */
    public function execute(): Page
    {
        $id = $this->getRequest()->getParam('id');
        try {
            $car = $this->carsRepository->get($id);
            $result = $this->pageFactory->create();
            $result->setActiveMenu('Magento_Catalog::catalog_products');
            $result->setActiveMenu('Voronin_Cars::Cars')
                ->addBreadcrumb(__('Edit Car'), __('Car'));
            $result->getConfig()
                ->getTitle()
                ->prepend(__('Edit Car Model: %car', ['car' => $car->getCarModel()]));
        } catch (NoSuchEntityException $e) {
            $result = $this->resultRedirectFactory->create();
            $this->messageManager->addErrorMessage(
                __('Product type with id "%value" does not exist.', ['value' => $id])
            );
            $result->setPath('*/*/');
        }
        return $result;
    }

    /**
     * Check user's rights
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Voronin_Cars::edit');
    }
}
