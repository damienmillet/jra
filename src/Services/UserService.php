<?php

/**
 * Service file for defining the UserService class.
 * php version 8.2
 *
 * @category Services
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Services;

use Entities\User;
use Managers\UserManager;
use Core\Validator\ValidatorFactory;

/**
 * Class UserService
 *
 * @category Services
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class UserService
{
    /**
     * List all users.
     *
     * @return array The list of users.
     */
    public static function getAll(): array
    {
        $userManager = new UserManager();
        $users       = $userManager->findAll();
        return ConvertService::toObjectArray($users);
    }


    /**
     * Find a user by ID.
     *
     * @param integer $id The ID of the user.
     *
     * @return User|null The user if found, null otherwise.
     */
    public static function getOneById(int $id): ?User
    {
        $userManager = new UserManager();
        return $userManager->findOneById($id);
    }

    /**
     * Find a user by username.
     *
     * @param string $username The username of the user.
     *
     * @return User|null The user if found, null otherwise.
     */
    public static function getOneByUsername(string $username): ?User
    {
        $userManager = new UserManager();
        return $userManager->findOneBy("name", $username);
    }


    /**
     * Prepare roles for the user.
     *
     * @param array $roles The roles to prepare.
     *
     * @return array The prepared roles.
     */
    public static function prepareRoles(array $roles): array
    {

        if (empty($roles)) {
            $roles = ['USER'];
        }

        // pour sécuriser l'application,
        // on s'assure que l'utilisateur a au moins un rôle
        if (self::hasAdmin($roles) && !self::hasUser($roles)) {
            $roles[] = 'USER';
        }

        return $roles;
    }


    /**
     * Check if the roles array contains 'ADMIN'.
     *
     * @param array $roles The roles to check.
     *
     * @return boolean True if 'ADMIN' is in the roles, false otherwise.
     */
    public static function hasAdmin(array $roles): bool
    {
        return in_array('ADMIN', $roles, true);
    }


    /**
     * Check if the roles array contains 'USER'.
     *
     * @param array $roles The roles to check.
     *
     * @return boolean True if 'USER' is in the roles, false otherwise.
     */
    public static function hasUser(array $roles): bool
    {
        return in_array('USER', $roles, true);
    }


    /**
     * Hash the given password.
     *
     * @param string $password The password to hash.
     *
     * @return string The hashed password.
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }


    /**
     * Prepare a User entity from JSON data.
     *
     * @param array $data The JSON data to prepare the user from.
     * @param User  $user (optionnal) The User entity to prepare.
     *
     * @return User The prepared User entity.
     */
    public static function prepare(array $data, User $user = new User()): User
    {
        if (isset($data['username'])) {
            $data['name'] = $data['username'];
            unset($data['username']);
        }
        if (isset($data['password'])) {
            $data['hash'] =  self::hashPassword($data['password']);
            unset($data['password']);
        }
        if (isset($data['roles'])) {
            $data['roles'] = self::prepareRoles($data['roles']);
        }

        return $user->hydrate($data);
    }

    /**
     * Validate the provided data.
     *
     * @param array $data The data to validate.
     *
     * @return bool True if the data is valid, false otherwise.
     */
    public static function isValide(array &$data): bool
    {
        $schema = [
            'username' => [
                'type' => 'string',
                'sanitize' => 'string',
                'required' => false,
            ],
            'password' => [
                'type' => 'string',
                'sanitize' => 'string',
                'required' => false,
            ],
        ];
        
        return ValidatorFactory::validate($data, $schema);
    }

    /**
     * Validate the provided data.
     *
     * @param array $data The data to validate.
     *
     * @return bool True if the data is valid, false otherwise.
     */
    public static function isValidePost(array &$data): bool
    {
        $schema = [
            'username' => [
                'type' => 'string',
                'sanitize' => 'string',
                'required' => true,
            ],
            'password' => [
                'type' => 'string',
                'sanitize' => 'string',
                'required' => true,
            ],
        ];

        return ValidatorFactory::validate($data, $schema);
    }
}
