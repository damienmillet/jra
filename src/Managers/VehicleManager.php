<?php

/**
 * Manager file for defining the VehicleManager class.
 * php version 8.2
 *
 * @category Managers
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Managers;

use Entities\Vehicle;
use Core\BddManager;
use Services\VehicleService;

/**
 * Class VehicleManager
 *
 * @category Managers
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class VehicleManager extends BddManager
{
    /**
     * Update vehicle in database.
     *
     * @param Vehicle $vehicle The vehicle object to update.
     *
     * @return Vehicle|null The updated vehicle object.
     */
    public function updateOne(Vehicle $vehicle): ?Vehicle
    {
        $pdo  = $this->getPdo();
        $stmt = $pdo->prepare(
            'UPDATE vehicles 
            SET name = :name, hash = :hash, roles = :roles
            WHERE id = :id'
        );

        $isUpdated = $stmt->execute(
            [
                ':name'  => $vehicle->getName(),
                ':hash'  => $vehicle->getHash(),
                ':roles' => ConvertService::arrayToJson($vehicle->getRoles()),
                ':id'    => $vehicle->getId(),
            ]
        );

        if (!$isUpdated) {
            return null;
        }

        // Récupération des données mises à jour
        $stmt = $pdo->prepare('SELECT * FROM vehicles WHERE id = :id');
        $stmt->execute([':id' => $vehicle->getId()]);

        return $stmt->fetchObject(Vehicle::class) ?: null;
    }


    /**
     * Insert a new vehicle into the database.
     *
     * @param Vehicle $vehicle The vehicle object to insert.
     *
     * @return Vehicle|null|string The inserted vehicle object or null if insertion failed.
     */
    public function insertOne(Vehicle $vehicle): Vehicle|string
    {
        $pdo = $this->getPdo();
        try {
            $exist = $this->exist('name', $vehicle->getName());

            if ($exist) {
                return 'vehicle name already exist';
            }

            $stmt = $pdo->prepare(
                'INSERT INTO vehicles (name, hash, roles) 
                VALUES (:name, :hash, :roles)'
            );
            $stmt->execute(
                [
                    ':name'  => $vehicle->getName(),
                    ':hash'  => $vehicle->getHash(),
                    ':roles' => ConvertService::arrayToJson($vehicle->getRoles()),
                ]
            );

            $insertedId = $pdo->lastInsertId();
            return $this->findOneById($insertedId);
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                return 'vehicle name already exist';
            }

            return $e->getMessage();
        }
    }
}
