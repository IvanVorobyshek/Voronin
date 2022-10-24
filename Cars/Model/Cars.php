<?php
namespace Voronin\Cars\Model;

use Magento\Framework\Model\AbstractModel;
use Voronin\Cars\Api\Data\CarInterface;
use Voronin\Cars\Model\ResourceModel\Cars as CarsResource;

class Cars extends AbstractModel implements CarInterface
{
    const CACHE_TAG = 'voronin_cars_cars';

    /**
     * Model cache tag for clear cache in after save and after delete
     *
     * @var string
     */
    protected $cacheTag = self::CACHE_TAG;

    /**
     * @var string
     */
    protected $eventPrefix = 'cars';

    /**
     * @var string
     */
    protected $idFieldName = CarInterface::CAR_ID;

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(CarsResource::class);
    }

    /**
     * @return mixed
     */
    public function getId():int|null
    {
        return $this->getData(self::CAR_ID);
    }

    /**
     * @param $id
     * @return CarInterface
     */
    public function setId($id):CarInterface
    {
        return $this->setData(self::CAR_ID, $id);
    }

    /**
     * @return string
     */
    public function getCarModel():string
    {
        return (string)$this->getData(self::CAR_MODEL);
    }

    /**
     * @param $carModel
     * @return CarInterface
     */
    public function setCarModel($carModel):CarInterface
    {
        return $this->setData(self::CAR_MODEL, $carModel);
    }

    /**
     * @return string
     */
    public function getCarManufacturer():string
    {
        return (string)$this->getData(self::CAR_MANUFACTURER);
    }

    /**
     * @param $carManufacturer
     * @return CarInterface
     */
    public function setCarManufacturer($carManufacturer):CarInterface
    {
        return $this->setData(self::CAR_MANUFACTURER, $carManufacturer);
    }

    /**
     * @return string
     */
    public function getCarDescription():string
    {
        return (string)$this->getData(self::CAR_DESCRIPTION);
    }

    /**
     * @param $carDescription
     * @return CarInterface
     */
    public function setCarDescription($carDescription):CarInterface
    {
        return $this->setData(self::CAR_DESCRIPTION, $carDescription);
    }

    /**
     * @return string
     */
    public function getCarRealeaseYear():int
    {
        return (int)$this->getData(self::CAR_RELEASE_YEAR);
    }

    /**
     * @param $carRealeaseYear
     * @return CarInterface
     */
    public function setCarReleaseYear($carRealeaseYear):CarInterface
    {
        return $this->setData(self::CAR_RELEASE_YEAR, $carRealeaseYear);
    }

    /**
     * @return string
     */
    public function getCarCreatedAt():string
    {
        return (string)$this->getData(self::CAR_CREATED_AT);
    }

    /**
     * @param $carCreatedAt
     * @return CarInterface
     */
    public function setCarCreatedAt($carCreatedAt):CarInterface
    {
        return $this->setData(self::CAR_CREATED_AT, $carCreatedAt);
    }

    /**
     * @return string
     */
    public function getCarUpdatedAt():string
    {
        return (string)$this->getData(self::CAR_UPDATED_AT);
    }

    /**
     * @param $carUpdatedAt
     * @return CarInterface
     */
    public function setCarUpdatedAt($carUpdatedAt):CarInterface
    {
        return $this->setData(self::CAR_UPDATED_AT, $carUpdatedAt);
    }
}
