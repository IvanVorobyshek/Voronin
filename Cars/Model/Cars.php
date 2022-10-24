<?php
namespace Voronin\Cars\Model;

use Magento\Framework\Model\AbstractModel;
use Voronin\Cars\Api\Data\CarInterface;
use Voronin\Cars\Model\ResourceModel\Cars as CarsResource;

class Cars extends AbstractModel implements CarInterface
{
    public const CACHE_TAG = 'voronin_cars_cars';

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
     * Get ID
     *
     * @return mixed
     */
    public function getId():int|null
    {
        return $this->getData(self::CAR_ID);
    }

    /**
     * Set ID
     *
     * @param int|null $id
     * @return CarInterface
     */
    public function setId($id):CarInterface
    {
        return $this->setData(self::CAR_ID, $id);
    }

    /**
     * Get Car Model
     *
     * @return string
     */
    public function getCarModel():string
    {
        return (string)$this->getData(self::CAR_MODEL);
    }

    /**
     * Set Car Model
     *
     * @param string $carModel
     * @return CarInterface
     */
    public function setCarModel(string $carModel):CarInterface
    {
        return $this->setData(self::CAR_MODEL, $carModel);
    }

    /**
     * Get Car Manufacturer
     *
     * @return string
     */
    public function getCarManufacturer():string
    {
        return (string)$this->getData(self::CAR_MANUFACTURER);
    }

    /**
     * Set Car Manufacturer
     *
     * @param string $carManufacturer
     * @return CarInterface
     */
    public function setCarManufacturer(string $carManufacturer):CarInterface
    {
        return $this->setData(self::CAR_MANUFACTURER, $carManufacturer);
    }

    /**
     * Get Car Description
     *
     * @return string
     */
    public function getCarDescription():string
    {
        return (string)$this->getData(self::CAR_DESCRIPTION);
    }

    /**
     * Set Car Description
     *
     * @param string $carDescription
     * @return CarInterface
     */
    public function setCarDescription(string $carDescription):CarInterface
    {
        return $this->setData(self::CAR_DESCRIPTION, $carDescription);
    }

    /**
     * Get Car Release Year
     *
     * @return string
     */
    public function getCarRealeaseYear():int
    {
        return (int)$this->getData(self::CAR_RELEASE_YEAR);
    }

    /**
     * Set Car Release Year
     *
     * @param int $carRealeaseYear
     * @return CarInterface
     */
    public function setCarReleaseYear(int $carRealeaseYear):CarInterface
    {
        return $this->setData(self::CAR_RELEASE_YEAR, $carRealeaseYear);
    }

    /**
     * Get Date Car Created
     *
     * @return string
     */
    public function getCarCreatedAt():string
    {
        return (string)$this->getData(self::CAR_CREATED_AT);
    }

    /**
     * Set Date Car Created
     *
     * @param string $carCreatedAt
     * @return CarInterface
     */
    public function setCarCreatedAt(string $carCreatedAt):CarInterface
    {
        return $this->setData(self::CAR_CREATED_AT, $carCreatedAt);
    }

    /**
     * Get Date Car Updated
     *
     * @return string
     */
    public function getCarUpdatedAt():string
    {
        return (string)$this->getData(self::CAR_UPDATED_AT);
    }

    /**
     * Set Date Car Updated
     *
     * @param string $carUpdatedAt
     * @return CarInterface
     */
    public function setCarUpdatedAt(string $carUpdatedAt):CarInterface
    {
        return $this->setData(self::CAR_UPDATED_AT, $carUpdatedAt);
    }
}
