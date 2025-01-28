<?php

/**
 * Entity file for defining the Historic Entity class.
 * php version 8.2
 *
 * @category Entities
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Entities;

use Core\EntityManager;

/**
 * Class HistoricEntity
 *
 * @category Entities
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class Historic extends EntityManager
{
    private int $_id = 0;
    private int $_contactId;
    private string $_columnName;
    private string $_oldValue;
    private string $_newValue;
    private string $_modifiedAt;
    private int $_modifiedBy;

    // Getters
    /**
     * Get the ID.
     *
     * @return int The ID.
     */
    public function getId(): int
    {
        return $this->_id;
    }

    /**
     * Get the contact ID.
     *
     * @return int The contact ID.
     */
    public function getContactId(): int
    {
        return $this->_contactId;
    }

    /**
     * Get the column name.
     *
     * @return string The column name.
     */
    public function getColumnName(): string
    {
        return $this->_columnName;
    }

    /**
     * Get the old value.
     *
     * @return string The old value.
     */
    public function getOldValue(): string
    {
        return $this->_oldValue;
    }

    /**
     * Get the new value.
     *
     * @return string The new value.
     */
    public function getNewValue(): string
    {
        return $this->_newValue;
    }

    /**
     * Get the modified at date.
     *
     * @return string The modified at date.
     */
    public function getModifiedAt(): string
    {
        return $this->_modifiedAt;
    }

    /**
     * Get the modified by user ID.
     *
     * @return int The modified by user ID.
     */
    public function getModifiedBy(): int
    {
        return $this->_modifiedBy;
    }

    // Setters
    /**
     * Set the ID.
     *
     * @param int $id The ID.
     * 
     * @return Historic
     */
    public function setId(int $id): static
    {
        $this->_id = $id;
        return $this;
    }

    /**
     * Set the contact ID.
     *
     * @param int $contactId The contact ID.
     * 
     * @return Historic
     */
    public function setContactId(int $contactId): static
    {
        $this->_contactId = $contactId;
        return $this;
    }

    /**
     * Set the column name.
     *
     * @param string $columnName The column name.
     * 
     * @return Historic
     */
    public function setColumnName(string $columnName): static
    {
        $this->_columnName = $columnName;
        return $this;
    }

    /**
     * Set the old value.
     *
     * @param string $oldValue The old value.
     * 
     * @return Historic
     */
    public function setOldValue(string $oldValue): static
    {
        $this->_oldValue = $oldValue;
        return $this;
    }

    /**
     * Set the new value.
     *
     * @param string $newValue The new value.
     * 
     * @return Historic
     */
    public function setNewValue(string $newValue): static
    {
        $this->_newValue = $newValue;
        return $this;
    }

    /**
     * Set the modified at date.
     *
     * @param string $modifiedAt The modified at date.
     * 
     * @return Historic
     */
    public function setModifiedAt(string $modifiedAt): static
    {
        $this->_modifiedAt = $modifiedAt;
        return $this;
    }

    /**
     * Set the modified by user ID.
     *
     * @param int $modifiedBy The modified by user ID.
     * 
     * @return Historic
     */
    public function setModifiedBy(int $modifiedBy): static
    {
        $this->_modifiedBy = $modifiedBy;
        return $this;
    }

    /**
     * Converts the historic object to an array.
     *
     * @return array The historic object as an array.
     */
    public function toArray(): array
    {
        return [
            'id'         => $this->getId(),
            'contactId'  => $this->getContactId(),
            'columnName' => $this->getColumnName(),
            'oldValue'   => $this->getOldValue(),
            'newValue'   => $this->getNewValue(),
            'modifiedAt' => $this->getModifiedAt(),
            'modifiedBy' => $this->getModifiedBy(),
        ];
    }

    /**
     * Converts the historic object to a JSON string.
     *
     * @return string The historic object as a JSON string.
     */
    public function __toString(): string
    {
        return json_encode($this->toArray());
    }
}
