<?php

namespace Entities;

use Core\Auth\Role;
use Core\EntityManager;

/**
 * Class UserEntity
 */
class User extends EntityManager
{
    private int $id = 0;
    private string $name;
    private array $roles = [];
    private string $hash;

    /**
     * Setter of the user ID.
     *
     * @param integer $id The user ID.
     *
     * @return User
     */
    public function setId(int $id): User
    {
        $this->id = $id;
        return $this;
    }


    /**
     * Setter of the name.
     *
     * @param string $name The name.
     *
     * @return User
     */
    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }


    /**
     * Setter of the user roles.
     *
     * @param array $roles The user roles.
     *
     * @return User
     */
    public function setRoles(array $roles): User
    {
        $this->roles = $roles;
        return $this;
    }


    /**
     * Getter for the user hash.
     *
     * @return string The user hash.
     */
    public function getHash(): string
    {
        return $this->hash;
    }


    /**
     * Setter for the user hash.
     *
     * @param string $hash The user hash.
     *
     * @return User
     */
    public function setHash(string $hash): User
    {
        $this->hash = $hash;
        return $this;
    }


    /**
     * Getter for the user roles.
     *
     * @return array The user roles.
     */
    public function getRoles(): array
    {
        return $this->roles;
    }


    /**
     * Adds a role to the user.
     *
     * @param Role $role The role to add.
     *
     * @return void
     */
    public function addRole(Role $role): void
    {
        $this->roles[] = $role;
    }


    /**
     * Deletes a role from the user.
     *
     * @param Role $role The role to delete.
     *
     * @return void
     */
    public function delRole(Role $role): void
    {
        $key = array_search($role, $this->roles);
        if ($key !== false) {
            unset($this->roles[$key]);
        }
    }


    /**
     * Getter for the user ID.
     *
     * @return integer The user ID.
     */
    public function getId(): int
    {
        return $this->id;
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
     * Converts the user object to an array.
     *
     * @return array The user object as an array.
     */
    public function toArray(): array
    {
        return [
            'id'    => $this->getId(),
            'name'  => $this->getName(),
            'roles' => $this->getRoles(),
        ];
    }


    /**
     * Converts the user object to a JSON string.
     *
     * @return string The user object as a JSON string.
     */
    public function __toString(): string
    {
        return json_encode($this->toArray());
    }
}
