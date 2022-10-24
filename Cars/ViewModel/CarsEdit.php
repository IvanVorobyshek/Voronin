<?php

namespace Voronin\Cars\ViewModel;

use Magento\Framework\App\Request\Http\Proxy;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Voronin\Cars\Api\CarRepositoryInterface;
use Voronin\Cars\Api\Data\CarInterface;

class CarsEdit implements ArgumentInterface
{
    /**
     * @var Proxy
     */
    protected $request;

    /**
     * @var CarRepositoryInterface
     */
    private CarRepositoryInterface $carRepository;

    /**
     * @param Proxy $request
     * @param CarRepositoryInterface $carRepository
     * @param array $data
     */
    public function __construct(
        Proxy $request,
        CarRepositoryInterface $carRepository,
        array $data = []
    ) {
        $this->request = $request;
        $this->carRepository = $carRepository;
    }

    /**
     * @return CarInterface
     */
    public function getOutData()
    {
        $id = (int)$this->request->getParam('id');
        if ($id !== 0) {
            $model = $this->carRepository->get($id);
            return $model;
        }
        return null;
    }
}
