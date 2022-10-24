<?php

namespace Voronin\Cars\Ui\Component\Form\Cars;

use Magento\Framework\Exception\NoSuchEntityException;
use Voronin\Cars\Model\CarsRepository;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;

class GenericButton
{
    /**
     * @var UrlInterface
     */
    private UrlInterface $urlBuilder;
    /**
     * @var RequestInterface
     */
    private RequestInterface $request;
    /**
     * @var CarsRepository
     */
    private CarsRepository $carsRepository;

    /**
     * @param UrlInterface $urlBuilder
     * @param RequestInterface $request
     * @param CarsRepository $carsRepository
     */
    public function __construct(
        UrlInterface $urlBuilder,
        RequestInterface $request,
        CarsRepository $carsRepository
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->request = $request;
        $this->carsRepository = $carsRepository;
    }

    /**
     * Build url by requested path and parameters
     *
     * @param   string|null $route
     * @param   array|null $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->urlBuilder->getUrl($route, $params);
    }

    /**
     * Get Car
     *
     * @return int|null
     * @throws NoSuchEntityException
     */
    public function getCar()
    {
        $carId = $this->request->getParam('id');
        if ($carId === null) {
            return 0;
        }
        $car = $this->carsRepository->get($carId);
        return $car->getId() ?: null;
    }
}
