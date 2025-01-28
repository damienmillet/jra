<?php

/**
 * Entity file for defining the User Entity class.
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
 * Class UserEntity
 *
 * @category Entities
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class User extends EntityManager
{
    private int $_id = 0;
    private string $_name;
    private array $_roles = [];
    private string $_hash;

    /**
     * Setter of the user ID.
     *
     * @param integer $id The user ID.
     *
     * @return User
     */
    public function setId(int $id): User
    {
        $this->_id = $id;
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
        $this->_name = $name;
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
        $this->_roles = $roles;
        return $this;
    }


    /**
     * Getter for the user hash.
     *
     * @return string The user hash.
     */
    public function getHash(): string
    {
        return $this->_hash;
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
        $this->_hash = $hash;
        return $this;
    }


    /**
     * Getter for the user roles.
     *
     * @return array The user roles.
     */
    public function getRoles(): array
    {
        return $this->_roles;
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
        $this->_roles[] = $role;
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
        $key = array_search($role, $this->_roles);
        if ($key !== false) {
            unset($this->_roles[$key]);
        }
    }


    /**
     * Getter for the user ID.
     *
     * @return integer The user ID.
     */
    public function getId(): int
    {
        return $this->_id;
    }


    /**
     * Getter for the name.
     *
     * @return string The name.
     */
    public function getName(): string
    {
        return $this->_name;
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
