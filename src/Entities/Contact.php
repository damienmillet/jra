<?php

namespace Entities;

use Core\EntityManager;

/**
 * Class ContactEntity
 */
class Contact extends EntityManager
{
    private int $id = 0;

    private string $name;

    private string $email;

    private string $createdAt;

    private string $modifiedAt;


    /**
     * Getter for the email.
     *
     * @return string The email.
     */
    public function getEmail(): string
    {
        return $this->email;
    }


    /**
     * Setter for the email.
     *
     * @param string $email The email.
     *
     * @return Contact
     */
    public function setEmail(string $email): Contact
    {
        $this->email = $email;
        return $this;
    }


    /**
     * Getter for the created at timestamp.
     *
     * @return string The created at timestamp.
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }


    /**
     * Setter for the created at timestamp.
     *
     * @param string $createdAt The created at timestamp.
     *
     * @return Contact
     */
    public function setCreatedAt(string $createdAt): Contact
    {
        $this->createdAt = $createdAt;
        return $this;
    }


    /**
     * Getter for the updated at timestamp.
     *
     * @return string The updated at timestamp.
     */
    public function getModifiedAt(): string
    {
        return $this->modifiedAt;
    }


    /**
     * Setter for the updated at timestamp.
     *
     * @param string $modifiedAt The updated at timestamp.
     *
     * @return Contact
     */
    public function setModifiedAt(string $modifiedAt): Contact
    {
        $this->modifiedAt = $modifiedAt;
        return $this;
    }


    /**
     * Getter for the contact ID.
     *
     * @return integer The contact ID.
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * Setter of the contact ID.
     *
     * @param integer $id The contact ID.
     *
     * @return Contact
     */
    public function setId(int $id): Contact
    {
        $this->id = $id;
        return $this;
    }


    /**
     * Getter for the name.
     *
     * @return string The name.
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * Setter of the name.
     *
     * @param string $name The name.
     *
     * @return Contact
     */
    public function setName(string $name): Contact
    {
        $this->name = $name;
        return $this;
    }


    /**
     * Converts the contact object to an array.
     *
     * @return array<mixed> The contact object as an array.
     */
    public function toArray(): array
    {
        return [
            'id'         => $this->getId(),
            'name'       => $this->getName(),
            'email'      => $this->getEmail(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getModifiedAt(),
        ];
    }


    /**
     * Converts the contact object to a JSON string.
     *
     * @return string The contact object as a JSON string.
     */
    public function __toString(): string
    {
        return json_encode($this->toArray());
    }
}
