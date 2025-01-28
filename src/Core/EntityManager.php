<?php

/**
 * Core file for defining the Entity Manager class.
 * php version 8.2
 *
 * @category Entities
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Core;

/**
 * Class EntityManager
 *
 * @category Entities
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
abstract class EntityManager
{
    /**
     * Magic setter method.
     *
     * @param string $name  The property name.
     * @param mixed  $value The property value.
     *
     * @return EntityManager The entity object.
     */
    public function __set($name, $value)
    {
        $property = $this->_getProperty($name);

        if ($property) {
            $property->setAccessible(true);
            if ($property->getName() === '_roles' && !is_array($value)) {
                $value = json_decode($value, true);
            }

            $property->setValue($this, $value);
        }

        return $this;
    }


    /**
     * Magic getter method.
     *
     * @param string $name The property name.
     *
     * @return mixed|null The property value or null if not found.
     */
    public function __get($name)
    {
        $property = $this->_getProperty($name);

        if ($property) {
            $property->setAccessible(true);
            return $property->getValue($this);
        }

        return null;
    }


    /**
     * Get the ReflectionProperty for a given property name.
     *
     * @param string $name The property name.
     *
     * @return \ReflectionProperty|null The ReflectionProperty object
     * or null if not found.
     */
    private function _getProperty(string $name): ?\ReflectionProperty
    {
        $reflectionClass = new \ReflectionClass($this);
        $propertyNames   = [
            '_' . $name,
            '_' . lcfirst(
                str_replace(' ', '', ucwords(str_replace('_', ' ', $name)))
            ),
        ];

        foreach ($propertyNames as $propertyName) {
            if ($reflectionClass->hasProperty($propertyName)) {
                return $reflectionClass->getProperty($propertyName);
            }
        }

        return null;
    }


    /**
     * Converts the object to an array.
     *
     * @return array The object as an array.
     */
    public function toArray(): array
    {
        $array           = [];
        $reflectionClass = new \ReflectionClass($this);
        $properties      = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($this);
        }

        return $array;
    }

    /**
     * Constructor method.
     *
     * @param array|null $data The data to initialize the entity with.
     */
    public function __construct($data = null) 
    {
        if ($data) {
            $this->hydrate($data);
        }
    }

    /**
     * Hydrates the entity with data.
     *
     * @param array $data The data to hydrate the entity with.
     *
     * @return static The entity object.
     */
    public function hydrate($data): static 
    {
        // Boucle sur les éléments du tableau $data
        foreach ($data as $key => $value) {
            // Construire le nom du setter
            $setter = 'set' . ucfirst($key);
            // Vérifier si le setter existe dans la classe
            if (method_exists($this, $setter)) {
                // Appeler dynamiquement le setter
                $this->$setter($value);
            }
        }
        return $this;
    }
}
