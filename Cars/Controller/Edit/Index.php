<?php
namespace Voronin\Cars\Controller\Edit;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Forward;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Voronin\Cars\Model\Config;

class Index implements ActionInterface
{
    /**
     * @var PageFactory
     */
    protected PageFactory $pageFactory;

    /**
     * @var ForwardFactory
     */
    protected ForwardFactory $forwardFactory;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param ForwardFactory $forwardFactory
     * @param Config $config
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        ForwardFactory $forwardFactory,
        Config $config
    ) {
        $this->pageFactory = $pageFactory;
        $this->config = $config;
        $this->forwardFactory = $forwardFactory;
    }

    /**
     * Create a page to edit a car
     *
     * @return ResponseInterface|Forward|ResultInterface|Page
     */
    public function execute()
    {
        if ($this->config->isEnabled()) {
            return $this->pageFactory->create();
        } else {
            //404
            $forward = $this->forwardFactory->create()->forward('defaultNoRoute');
            return $forward;
        }
    }
}
