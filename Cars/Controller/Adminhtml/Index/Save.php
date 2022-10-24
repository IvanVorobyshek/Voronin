<?php

namespace Voronin\Cars\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Voronin\Cars\Model\CarsFactory;
use Voronin\Cars\Api\CarRepositoryInterface;

class Save extends Action
{
    /**
     * @var CarsFactory|\Voronin\Cars\Model\CarsFactory
     */
    protected $carsFactory;

    /**
     * @var CarRepositoryInterface
     */
    private CarRepositoryInterface $carRepository;

    /**
     * @param Context $context
     * @param CarRepositoryInterface $carRepository
     * @param CarsFactory $carsFactory
     */
    public function __construct(
        Context $context,
        CarRepositoryInterface $carRepository,
        CarsFactory $carsFactory
    ) {
        $this->carsFactory = $carsFactory;
        $this->carRepository = $carRepository;
        parent::__construct($context);
    }

    /**
     * Saving a car
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $params = $data['general'];
        //go to the listing page, if there is no params
        if (!$params) {
            $this->messageManager->addErrorMessage(__('Car data no longer exists.'));
            $this->_redirect('*/*/');
        }
        if (isset($params['car_id'])) {
            //updating existing car
            $car = $this->carRepository->get((int)$params['car_id']);
        } else {
            //adding new car
            $car = $this->carsFactory->create();
        }
        try {
            $car->setCarModel($params['car_model']);
            $car->setCarManufacturer($params['car_manufacturer']);
            $car->setCarDescription($params['car_description']);
            $car->setCarReleaseYear((int)$params['car_release_year']);
            $this->carRepository->save($car);
            $this->messageManager->addSuccessMessage(__('Car data has been successfully saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
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
        return $this->_authorization->isAllowed('Voronin_Cars::save');
    }
}
