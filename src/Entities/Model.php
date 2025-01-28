<?php

/**
 * Entity file for defining the Model Entity class.
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
 * Class ModelEntity
 *
 * @category Entities
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class Model extends EntityManager
{
    private int $_id = 0;

    private string $_brand;

    private string $_name;

    private string $_model;

    private string $_version;

    private string $_year;

    private string $_price;

    private string $_category;

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
     * Get the value of _brand
     *
     * @return string
     */
    public function getBrand(): string
    {
        return $this->_brand;
    }


    /**
     * Set the value of _brand
     *
     * @param string $brand 
     *
     * @return self
     */
    public function setBrand(string $brand): self
    {
        $this->_brand = $brand;

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
     * Get the value of _model
     *
     * @return string
     */
    public function getModel(): string
    {
        return $this->_model;
    }


    /**
     * Set the value of _model
     *
     * @param string $model 
     *
     * @return self
     */
    public function setModel(string $model): self
    {
        $this->_model = $model;

        return $this;
    }


    /**
     * Get the value of _version
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->_version;
    }


    /**
     * Set the value of _version
     *
     * @param string $version 
     *
     * @return self
     */
    public function setVersion(string $version): self
    {
        $this->_version = $version;

        return $this;
    }


    /**
     * Get the value of _year
     *
     * @return string
     */
    public function getYear(): string
    {
        return $this->_year;
    }


    /**
     * Set the value of _year
     *
     * @param string $year 
     *
     * @return self
     */
    public function setYear(string $year): self
    {
        $this->_year = $year;

        return $this;
    }


    /**
     * Get the value of _price
     *
     * @return string
     */
    public function getPrice(): string
    {
        return $this->_price;
    }


    /**
     * Set the value of _price
     *
     * @param string $price 
     *
     * @return self
     */
    public function setPrice(string $price): self
    {
        $this->_price = $price;

        return $this;
    }


    /**
     * Get the value of _category
     *
     * @return string
     */
    public function getCategory(): string
    {
        return $this->_category;
    }


    /**
     * Set the value of _category
     *
     * @param string $category 
     *
     * @return self
     */
    public function setCategory(string $category): self
    {
        $this->_category = $category;

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
     * Converts the model object to an array.
     *
     * @return array The model object as an array.
     */
    public function toArray(): array
    {
        return [
            'id'         => $this->getId(),
            'brand'      => $this->getBrand(),
            'name'       => $this->getName(),
            'model'      => $this->getModel(),
            'version'    => $this->getVersion(),
            'year'       => $this->getYear(),
            'price'      => $this->getPrice(),
            'category'   => $this->getCategory(),
            'createdAt'  => $this->getCreatedAt(),
            'modifiedAt' => $this->getModifiedAt(),
        ];
    }


    /**
     * Converts the model object to a JSON string.
     *
     * @return string The model object as a JSON string.
     */
    public function __toString(): string
    {
        return json_encode($this->toArray());
    }
}
