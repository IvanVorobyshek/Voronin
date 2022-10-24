<?php
namespace Voronin\Cars\Controller\Delete;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Forward;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface;
use Voronin\Cars\Model\Config;
use Voronin\Cars\Api\CarRepositoryInterface;
use Magento\Framework\App\Request\Http;

class Index implements HttpGetActionInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var CarRepositoryInterface
     */
    private CarRepositoryInterface $carRepository;

    /**
     * @var ForwardFactory
     */
    protected ForwardFactory $forwardFactory;

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
     * @param Context $context
     * @param ForwardFactory $forwardFactory
     * @param RedirectFactory $redirectFactory
     * @param Http $request
     * @param CarRepositoryInterface $carRepository
     * @param ManagerInterface $messageManager
     * @param Config $config
     */
    public function __construct(
        Context $context,
        ForwardFactory $forwardFactory,
        RedirectFactory $redirectFactory,
        Http $request,
        CarRepositoryInterface $carRepository,
        ManagerInterface $messageManager,
        Config $config
    ) {
        $this->config = $config;
        $this->request = $request;
        $this->forwardFactory = $forwardFactory;
        $this->redirectFactory = $redirectFactory;
        $this->carRepository = $carRepository;
        $this->messageManager = $messageManager;
    }

    /**
     * Deleting a car
     *
     * @return ResponseInterface|Forward|Redirect|ResultInterface
     */
    public function execute()
    {
        if ($this->config->isEnabled()) {
            $id = (int)$this->request->getParam('id');
            try {
                $this->carRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('Car data has been successfully deleted.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Something is wrong! Can\'t delete car data from DB!'));
            }
            //redirect to cars page
            $redirect = $this->redirectFactory->create();
            $redirect->setPath('*/');
            return $redirect;
        } else {
            //404
            $forward = $this->forwardFactory->create()->forward('defaultNoRoute');
            return $forward;
        }
    }
}
