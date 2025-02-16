<?php

namespace Entities;

use Core\Auth\Role;
use Core\EntityManager;

/**
 * Class ModelEntity
 */
class Model extends EntityManager
{
    private int $id = 0;

    private string $brand;

    private string $name;

    private string $model;

    private string $version;

    private string $year;

    private string $price;

    private string $category;

    private string $createdAt;

    private string $modifiedAt;


    /**
     * Get the value of _id
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
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
        $this->id = $id;

        return $this;
    }


    /**
     * Get the value of _brand
     *
     * @return string
     */
    public function getBrand(): string
    {
        return $this->brand;
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
        $this->brand = $brand;

        return $this;
    }


    /**
     * Get the value of _name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
        $this->name = $name;

        return $this;
    }


    /**
     * Get the value of _model
     *
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
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
        $this->model = $model;

        return $this;
    }


    /**
     * Get the value of _version
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
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
        $this->version = $version;

        return $this;
    }


    /**
     * Get the value of _year
     *
     * @return string
     */
    public function getYear(): string
    {
        return $this->year;
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
        $this->year = $year;

        return $this;
    }


    /**
     * Get the value of _price
     *
     * @return string
     */
    public function getPrice(): string
    {
        return $this->price;
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
        $this->price = $price;

        return $this;
    }


    /**
     * Get the value of _category
     *
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
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
        $this->category = $category;

        return $this;
    }


    /**
     * Get the value of _createdAt
     *
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
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
        $this->createdAt = $createdAt;

        return $this;
    }


    /**
     * Get the value of _modifiedAt
     *
     * @return string
     */
    public function getModifiedAt(): string
    {
        return $this->modifiedAt;
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
        $this->modifiedAt = $modifiedAt;

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
