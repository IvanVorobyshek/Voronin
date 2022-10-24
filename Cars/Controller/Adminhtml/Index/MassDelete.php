<?php

namespace Voronin\Cars\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Voronin\Cars\Model\CarsFactory;
use Magento\Backend\App\Action;
use Voronin\Cars\Api\CarRepositoryInterface;

class MassDelete extends Action
{
    /**
     * @var CarsFactory
     */
    private $carsFactory;

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
     * Deleting the car(s)
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $Ids = $data['selected'];
        $sizeIds = count($Ids);
        try {
            foreach ($Ids as $Id) {
                $this->carRepository->deleteById($Id);
            }
            $sizeIds === 1 ? $this->messageManager->addSuccessMessage(__('Car data has been successfully deleted.')) :
            $this->messageManager->addSuccessMessage(__('Cars data has been successfully deleted.'));
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
        return $this->_authorization->isAllowed('Voronin_Cars::massDelete');
    }
}
