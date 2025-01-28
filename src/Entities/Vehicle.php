<?php

/**
 * Entity file for defining the Vehicle Entity class.
 * php version 8.2
 *
 * @category Entities
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Entities;

use Core\Auth\Role;
use Core\EntityManager;

/**
 * Class VehicleEntity
 *
 * @category Entities
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class Vehicle extends EntityManager
{
    private int $_id;

    private string $_name;

    private int $_modelId;

    private float $_buyPrice;

    private string $_buyDate;

    private string $_type;

    private string $_fuel;

    private int $_km;

    private int $_cv;

    private string $_color;

    private string $_transmission;

    private int $_doors;

    private int $_seats;

    private string $_createdAt;

    private string $_modifiedAt;


    /**
     * Get the value of _id
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->_id;
    }


    /**
     * Set the value of _id
     *
     * @param integer $id
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->_id = $id;
        return $this;
    }


    /**
     * Get the value of _name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->_name;
    }


    /**
     * Set the value of _name
     *
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->_name = $name;
        return $this;
    }


    /**
     * Get the value of _modelId
     *
     * @return integer
     */
    public function getModelId(): int
    {
        return $this->_modelId;
    }


    /**
     * Set the value of _modelId
     *
     * @param integer $modelId
     *
     * @return self
     */
    public function setModelId(int $modelId): self
    {
        $this->_modelId = $modelId;
        return $this;
    }


    /**
     * Get the value of _buyPrice
     *
     * @return float
     */
    public function getBuyPrice(): float
    {
        return $this->_buyPrice;
    }


    /**
     * Set the value of _buyPrice
     *
     * @param float $buyPrice
     *
     * @return self
     */
    public function setBuyPrice(float $buyPrice): self
    {
        $this->_buyPrice = $buyPrice;
        return $this;
    }


    /**
     * Get the value of _buyDate
     *
     * @return string
     */
    public function getBuyDate(): string
    {
        return $this->_buyDate;
    }


    /**
     * Set the value of _buyDate
     *
     * @param string $buyDate
     *
     * @return self
     */
    public function setBuyDate(string $buyDate): self
    {
        $this->_buyDate = $buyDate;
        return $this;
    }


    /**
     * Get the value of _type
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->_type;
    }


    /**
     * Set the value of _type
     *
     * @param string $type
     *
     * @return self
     */
    public function setType(string $type): self
    {
        $this->_type = $type;
        return $this;
    }


    /**
     * Get the value of _fuel
     *
     * @return string
     */
    public function getFuel(): string
    {
        return $this->_fuel;
    }


    /**
     * Set the value of _fuel
     *
     * @param string $fuel
     *
     * @return self
     */
    public function setFuel(string $fuel): self
    {
        $this->_fuel = $fuel;
        return $this;
    }


    /**
     * Get the value of _km
     *
     * @return integer
     */
    public function getKm(): int
    {
        return $this->_km;
    }


    /**
     * Set the value of _km
     *
     * @param integer $km
     *
     * @return self
     */
    public function setKm(int $km): self
    {
        $this->_km = $km;
        return $this;
    }


    /**
     * Get the value of _cv
     *
     * @return integer
     */
    public function getCv(): int
    {
        return $this->_cv;
    }


    /**
     * Set the value of _cv
     *
     * @param integer $cv
     *
     * @return self
     */
    public function setCv(int $cv): self
    {
        $this->_cv = $cv;
        return $this;
    }


    /**
     * Get the value of _color
     *
     * @return string
     */
    public function getColor(): string
    {
        return $this->_color;
    }


    /**
     * Set the value of _color
     *
     * @param string $color
     *
     * @return self
     */
    public function setColor(string $color): self
    {
        $this->_color = $color;
        return $this;
    }


    /**
     * Get the value of _transmission
     *
     * @return string
     */
    public function getTransmission(): string
    {
        return $this->_transmission;
    }


    /**
     * Set the value of _transmission
     *
     * @param string $transmission
     *
     * @return self
     */
    public function setTransmission(string $transmission): self
    {
        $this->_transmission = $transmission;
        return $this;
    }


    /**
     * Get the value of _doors
     *
     * @return integer
     */
    public function getDoors(): int
    {
        return $this->_doors;
    }


    /**
     * Set the value of _doors
     *
     * @param integer $doors
     *
     * @return self
     */
    public function setDoors(int $doors): self
    {
        $this->_doors = $doors;
        return $this;
    }


    /**
     * Get the value of _seats
     *
     * @return integer
     */
    public function getSeats(): int
    {
        return $this->_seats;
    }


    /**
     * Set the value of _seats
     *
     * @param integer $seats
     *
     * @return self
     */
    public function setSeats(int $seats): self
    {
        $this->_seats = $seats;
        return $this;
    }


    /**
     * Get the value of _createdAt
     *
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->_createdAt;
    }


    /**
     * Set the value of _createdAt
     *
     * @param string $createdAt
     *
     * @return self
     */
    public function setCreatedAt(string $createdAt): self
    {
        $this->_createdAt = $createdAt;
        return $this;
    }


    /**
     * Get the value of _modifiedAt
     *
     * @return string
     */
    public function getModifiedAt(): string
    {
        return $this->_modifiedAt;
    }


    /**
     * Set the value of _modifiedAt
     *
     * @param string $modifiedAt
     *
     * @return self
     */
    public function setModifiedAt(string $modifiedAt): self
    {
        $this->_modifiedAt = $modifiedAt;
        return $this;
    }


    /**
     * Converts the vehicle object to an array.
     *
     * @return array The vehicle object as an array.
     */
    public function toArray(): array
    {
        return [
            'id'           => $this->getId(),
            'name'         => $this->getName(),
            'modelId'      => $this->getModelId(),
            'buyPrice'     => $this->getBuyPrice(),
            'buyDate'      => $this->getBuyDate(),
            'type'         => $this->getType(),
            'fuel'         => $this->getFuel(),
            'km'           => $this->getKm(),
            'cv'           => $this->getCv(),
            'color'        => $this->getColor(),
            'transmission' => $this->getTransmission(),
            'doors'        => $this->getDoors(),
            'seats'        => $this->getSeats(),
            'createdAt'    => $this->getCreatedAt(),
            'modifiedAt'   => $this->getModifiedAt(),
        ];
    }


    /**
     * Converts the vehicle object to a JSON string.
     *
     * @return string The vehicle object as a JSON string.
     */
    public function __toString(): string
    {
        return json_encode($this->toArray());
    }
}
