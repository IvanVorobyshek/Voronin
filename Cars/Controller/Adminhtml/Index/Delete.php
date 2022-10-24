<?php

namespace Voronin\Cars\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Voronin\Cars\Model\CarsFactory;
use Voronin\Cars\Api\CarRepositoryInterface;

class Delete extends Action
{
    /**
     * @var CarsFactory
     */
    protected $carsFactory;

    /**
     * @var CarRepositoryInterface
     */
    private CarRepositoryInterface $carRepository;

    /**
     * @param Context $context
     * @param CarsFactory $carsFactory
     * @param CarRepositoryInterface $carRepository
     */
    public function __construct(
        Context $context,
        CarsFactory $carsFactory,
        CarRepositoryInterface $carRepository
    ) {
        $this->carsFactory = $carsFactory;
        $this->carRepository = $carRepository;
        parent::__construct($context);
    }

    /**
     * Deleting the car
     *
     * @return ResponseInterface|ResultInterface|void
     */
    public function execute()
    {
        $carId = (int)$this->getRequest()->getParam('id');
        if (!$carId) {
            $this->messageManager->addErrorMessage(__('Car ID is 0!'));
            $this->_redirect('*/*/');
        }
        try {
            $this->carRepository->deleteById($carId);
            $this->messageManager->addSuccessMessage(__('Car data has been successfully deleted.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Something is wrong! Can\'t delete data from DB!'));
        }
        $this->_redirect('*/*/');
    }

    /**
     * Check user's rights
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Voronin_Cars::delete');
    }
}
