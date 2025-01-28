<?php

/**
 * Service file for defining the AuthService class.
 * php version 8.2
 *
 * @category Services
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Services;

use Services\UserService;
use Core\Validator\ValidatorFactory;
use Core\Auth;

/**
 * Class AuthService
 *
 * @category Services
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

class AuthService
{
    /**
     * Logs in a user with the provided username and password.
     *
     * @param string $username The username of the user.
     * @param string $password The password of the user.
     * 
     * @return string|false The generated token if login is successful, 
     *                      false otherwise.
     */
    public static function login($username, $password)
    {
        $user  = UserService::getOneByUsername($username);
        if ($user && Auth::verifyPassword($password, $user->getHash())) {
            return self::generateToken($user);
        }
        return false;
    }

    /**
     * Generates a token for the given user.
     *
     * @param object $user The user object for which the token is generated.
     *
     * @return string The generated token.
     */
    public static function generateToken($user): string 
    {
        $time  = new \DateTimeImmutable();
        return Auth::generateToken(
            [
                'id'    => $user->getId(),
                'name'  => $user->getName(),
                'roles' => $user->getRoles(),
                'exp'   => ($time->getTimestamp() + 3600),
            ]
        );
    }

    /**
     * Registers a new user with the provided user data.
     *
     * @param array $userData The data of the user to register.
     *
     * @return void
     */
    public function register($userData)
    {
        // inused for now
    }

    /**
     * Logs out the user by invalidating the provided token.
     *
     * @param string $token The token to be invalidated.
     *
     * @return void
     */
    public function logout($token)
    {
        // inused for now
    }

    /**
     * Refreshes the provided token by generating a new one with updated 
     * expiration time.
     *
     * @param string $token The token to be refreshed.
     *
     * @return void
     */
    public static function refreshToken(string &$token): void
    {
        $data = Auth::decodeToken($token);
        $time  = new \DateTimeImmutable();
        $token = Auth::generateToken(
            [
                'id'    => $data['id'],
                'name'  => $data['name'],
                'roles' => $data['roles'],
                'exp'   => ($time->getTimestamp() + 3600),
            ]
        );
    }

    /**
     * Generates a CSRF token.
     *
     * @return string The generated CSRF token.
     */
    public static function csrfFactory()
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * Validate the provided data.
     *
     * @param array $data The data to validate.
     *
     * @return bool True if the data is valid, false otherwise.
     */
    public static function isValide(&$data): bool
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
