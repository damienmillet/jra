<?php

/**
 * Manager file for defining the UserManager class.
 * php version 8.2
 *
 * @category Services
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Managers;

use Entities\User;
use Core\BddManager;
use Services\ConvertService;

/**
 * Class UserManager
 *
 * @category Managers
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class UserManager extends BddManager
{
    /**
     * Update user in database.
     *
     * @param User $user The user object to update.
     *
     * @return User|string The updated user object.
     */
    public function updateOne(User $user): User|string
    {
        try {
            $pdo  = $this->getPdo();
            $stmt = $pdo->prepare(
                'UPDATE users 
            SET name = :name, hash = :hash, roles = :roles 
            WHERE id = :id'
            );

            $isUpdated = $stmt->execute(
                [
                    ':name'  => $user->getName(),
                    ':hash'  => $user->getHash(),
                    ':roles' => ConvertService::arrayToJson($user->getRoles()),
                    ':id'    => $user->getId(),
                ]
            );

            if (!$isUpdated) {
                return null;
            }

            // Récupération des données mises à jour
            return $this->findOneById($user->getId());
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                return 'user name already exist';
            }

            return $e->getMessage();
        }
    }


    /**
     * Insert a new user into the database.
     *
     * @param User $user The user object to insert.
     *
     * @return User|null|string The inserted user object or null if insertion failed.
     */
    public function insertOne(User $user): User|string
    {
        $pdo = $this->getPdo();
        try {
            $exist = $this->exist('name', $user->getName());

            if ($exist) {
                return 'user name already exist';
            }

            $stmt = $pdo->prepare(
                'INSERT INTO users (name, hash, roles) 
                VALUES (:name, :hash, :roles)'
            );
            $stmt->execute(
                [
                    ':name'  => $user->getName(),
                    ':hash'  => $user->getHash(),
                    ':roles' => ConvertService::arrayToJson($user->getRoles()),
                ]
            );

            $insertedId = $pdo->lastInsertId();
            return $this->findOneById($insertedId);
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                return 'user name already exist';
            }

            return $e->getMessage();
        }
    }
}
