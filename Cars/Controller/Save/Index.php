<?php
namespace Voronin\Cars\Controller\Save;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Forward;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface;
use Voronin\Cars\Api\CarRepositoryInterface;
use Voronin\Cars\Model\CarsFactory;
use Voronin\Cars\Model\Config;

class Index implements HttpPostActionInterface
{
    /**
     * @var CarsFactory
     */
    private CarsFactory $carsFactory;

    /**
     * @var CarRepositoryInterface
     */
    private CarRepositoryInterface $carRepository;

    /**
     * @var ForwardFactory
     */
    protected ForwardFactory $forwardFactory;

    /**
     * @var Config
     */
    private Config $config;

    /**
     * @var Http
     */
    private Http $request;

    /**
     * @var RedirectFactory
     */
    private RedirectFactory $redirectFactory;

    /**
     * @var ManagerInterface
     */
    private ManagerInterface $messageManager;

    /**
     * @param ForwardFactory $forwardFactory
     * @param RedirectFactory $redirectFactory
     * @param CarRepositoryInterface $carRepository
     * @param CarsFactory $carsFactory
     * @param Http $request
     * @param ManagerInterface $messageManager
     * @param Config $config
     */
    public function __construct(
        ForwardFactory $forwardFactory,
        RedirectFactory $redirectFactory,
        CarRepositoryInterface $carRepository,
        CarsFactory $carsFactory,
        Http $request,
        ManagerInterface $messageManager,
        Config $config
    ) {
        $this->config = $config;
        $this->request = $request;
        $this->carsFactory = $carsFactory;
        $this->forwardFactory = $forwardFactory;
        $this->redirectFactory = $redirectFactory;
        $this->carRepository = $carRepository;
        $this->messageManager = $messageManager;
    }

    /**
     * Save(update) car data
     *
     * @return ResponseInterface|Forward|Redirect|ResultInterface
     */
    public function execute()
    {
        if ($this->config->isEnabled()) {
            $params = $this->request->getParams();
            if (isset($params['id'])) {
                //updating existing car
                $car = $this->carRepository->get((int)$params['id']);
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
                $this->messageManager->addSuccessMessage(__('Car data has been added to DB.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Something is wrong! Can\'t write data to DB!'));
            }
            //redirect to cars page
            $redirect = $this->redirectFactory->create();
            $redirect->setPath('*/');
        } else {
            //page 404
            $redirect = $this->forwardFactory->create()->forward('defaultNoRoute');
        }
        return $redirect;
    }
}
