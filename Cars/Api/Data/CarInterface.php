<?php

namespace Voronin\Cars\Api\Data;

interface CarInterface
{
    public const CAR_ID = 'car_id';
    public const CAR_MODEL = 'car_model';
    public const CAR_MANUFACTURER = 'car_manufacturer';
    public const CAR_DESCRIPTION = 'car_description';
    public const CAR_RELEASE_YEAR = 'car_release_year';
    public const CAR_CREATED_AT = 'car_created_at';
    public const CAR_UPDATED_AT = 'car_updated_at';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId():int|null;

    /**
     * Set ID
     *
     * @param int $id
     * @return mixed
     */
    public function setId(int $id);

    /**
     * Get CAR Model
     *
     * @return string
     */
    public function getCarModel():string;

    /**
     * Set CAR Model
     *
     * @param string $carModel
     * @return CarInterface
     */
    public function setCarModel(string $carModel):CarInterface;

    /**
     * Get Car Manufacturer
     *
     * @return string
     */
    public function getCarManufacturer():string;

    /**
     * Set Car Manufacturer
     *
     * @param string $carManufacturer
     * @return CarInterface
     */
    public function setCarManufacturer(string $carManufacturer):CarInterface;

    /**
     * Get Car Description
     *
     * @return string
     */
    public function getCarDescription():string;

    /**
     * Set  Car Description
     *
     * @param string $carDescription
     * @return CarInterface
     */
    public function setCarDescription(string $carDescription):CarInterface;

    /**
     * Get Car Release Year
     *
     * @return string
     */
    public function getCarRealeaseYear():int;

    /**
     * Set Car Release Year
     *
     * @param int $carRealeaseYear
     * @return CarInterface
     */
    public function setCarReleaseYear(int $carRealeaseYear):CarInterface;

    /**
     * Get Car creating date
     *
     * @return string
     */
    public function getCarCreatedAt():string;

    /**
     * Set Car creating date
     *
     * @param string $carCreatedAt
     * @return CarInterface
     */
    public function setCarCreatedAt(string $carCreatedAt):CarInterface;

    /**
     * Get Car updating date
     *
     * @return string
     */
    public function getCarUpdatedAt():string;

    /**
     * Set Car updating date
     *
     * @param string $carUpdatedAt
     * @return CarInterface
     */
    public function setCarUpdatedAt(string $carUpdatedAt):CarInterface;
}
