<?php

namespace Entities;

use Core\EntityManager;

/**
 * Class HistoricEntity
 */
class Historic extends EntityManager
{
    private int $id = 0;
    private int $contactId;
    private string $columnName;
    private string $oldValue;
    private string $newValue;
    private string $modifiedAt;
    private int $modifiedBy;

    // Getters
    /**
     * Get the ID.
     *
     * @return integer The ID.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the contact ID.
     *
     * @return integer The contact ID.
     */
    public function getContactId(): int
    {
        return $this->contactId;
    }

    /**
     * Get the column name.
     *
     * @return string The column name.
     */
    public function getColumnName(): string
    {
        return $this->columnName;
    }

    /**
     * Get the old value.
     *
     * @return string The old value.
     */
    public function getOldValue(): string
    {
        return $this->oldValue;
    }

    /**
     * Get the new value.
     *
     * @return string The new value.
     */
    public function getNewValue(): string
    {
        return $this->newValue;
    }

    /**
     * Get the modified at date.
     *
     * @return string The modified at date.
     */
    public function getModifiedAt(): string
    {
        return $this->modifiedAt;
    }

    /**
     * Get the modified by user ID.
     *
     * @return integer The modified by user ID.
     */
    public function getModifiedBy(): int
    {
        return $this->modifiedBy;
    }

    // Setters
    /**
     * Set the ID.
     *
     * @param integer $id The ID.
     *
     * @return Historic
     */
    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set the contact ID.
     *
     * @param integer $contactId The contact ID.
     *
     * @return Historic
     */
    public function setContactId(int $contactId): static
    {
        $this->contactId = $contactId;
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
        $this->columnName = $columnName;
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
        $this->oldValue = $oldValue;
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
        $this->newValue = $newValue;
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
        $this->modifiedAt = $modifiedAt;
        return $this;
    }

    /**
     * Set the modified by user ID.
     *
     * @param integer $modifiedBy The modified by user ID.
     *
     * @return Historic
     */
    public function setModifiedBy(int $modifiedBy): static
    {
        $this->modifiedBy = $modifiedBy;
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
