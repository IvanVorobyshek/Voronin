<?php

namespace Voronin\CoinsPayment\Controller\Index;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Forward;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Voronin\CoinsPayment\Model\Config;

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
     * @param PageFactory $pageFactory
     * @param ForwardFactory $forwardFactory
     * @param Config $config
     */
    public function __construct(
        PageFactory $pageFactory,
        ForwardFactory $forwardFactory,
        Config $config
    ) {
        $this->pageFactory = $pageFactory;
        $this->config = $config;
        $this->forwardFactory = $forwardFactory;
    }

    /**
     * Show customer's coin Page
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
