<?php

namespace Voronin\CoinsPayment\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Voronin\CoinsPayment\Api\CoinsRepositoryInterface;
use Voronin\CoinsPayment\Block\Adminhtml\Coins;
use Voronin\CoinsPayment\Model\CoinsFactory;

class SaveCoins extends Action
{
    /**
     * @var CoinsFactory
     */
    private CoinsFactory $coinsFactory;

    /**
     * @var Coins
     */
    private Coins $coins;

    /**
     * @var CoinsRepositoryInterface
     */
    private CoinsRepositoryInterface $coinsRepository;

    /**
     * @var JsonFactory
     */
    private JsonFactory $jsonFactor;

    /**
     * @var Json
     */
    private Json $json;

    /**
     * @param CoinsFactory $coinsFactory
     * @param CoinsRepositoryInterface $coinsRepository
     * @param Coins $coins
     * @param JsonFactory $jsonFactor
     * @param Json $json
     * @param Context $context
     */
    public function __construct(
        CoinsFactory $coinsFactory,
        CoinsRepositoryInterface $coinsRepository,
        Coins $coins,
        JsonFactory $jsonFactor,
        Json $json,
        Context $context
    ) {
        $this->coins = $coins;
        $this->coinsRepository = $coinsRepository;
        $this->coinsFactory = $coinsFactory;
        $this->jsonFactor = $jsonFactor;
        $this->json = $json;
        parent::__construct($context);
    }

    /**
     * Save new coin data and return updated data to frontend
     *
     * @return ResponseInterface|\Magento\Framework\Controller\Result\Json|ResultInterface
     */
    public function execute()
    {
        $result = [];
        $result['good'] = false;
        $result['error'] = '';
        $error = false;
        $req = $this->getRequest();
        $customerId = (int)$this->getRequest()->getParam('id');
        $coinsNum = (float)$this->getRequest()->getPostValue('coins');
        if (!$customerId || !$coinsNum || $coinsNum===0) {
            $result['error'] = 'Customer ID or Number of coins does not exist';
        } else {
            try {
                //save new row of coin
                $coin = $this->coinsFactory->create();
                $coin->setCustomerId($customerId);
                $coin->setIsOrderCompleted('1');
                $coin->setReceivedCoins($coinsNum);
                $this->coinsRepository->save($coin);
                $result = $this->coins->getCollection($customerId)->load()->getData();
                $result['total_coins'] = $this->coins->getTotalCoins($customerId);
                $result['good'] = true;
                $result['error'] = '';
            } catch (\Exception) {
                $result['error'] = 'Could not save data to DB';
            }
        }
        return $this->jsonFactor->create()->setData($result);
    }
}
