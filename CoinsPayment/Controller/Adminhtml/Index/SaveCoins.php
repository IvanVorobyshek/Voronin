<?php

namespace Voronin\CoinsPayment\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Voronin\CoinsPayment\Api\CoinsRepositoryInterface;
use Voronin\CoinsPayment\Block\Adminhtml\Coins;
use Voronin\CoinsPayment\Model\CoinsFactory;
use Voronin\CoinsPayment\Model\ResourceModel\Coins\Collection;

class SaveCoins extends Action
{
    private CoinsFactory $coinsFactory;

    private Coins $coins;

    private CoinsRepositoryInterface $coinsRepository;

    private Collection $collection;

    private JsonFactory $jsonFactor;

    private Json $json;

    public function __construct(
        CoinsFactory $coinsFactory,
        CoinsRepositoryInterface $coinsRepository,
        Coins $coins,
        Collection $collection,
        JsonFactory $jsonFactor,
        Json $json,
        Context $context
    ) {
        $this->coins = $coins;
        $this->collection = $collection;
        $this->coinsRepository = $coinsRepository;
        $this->coinsFactory = $coinsFactory;
        $this->jsonFactor = $jsonFactor;
        $this->json = $json;
        parent::__construct($context);
    }

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
