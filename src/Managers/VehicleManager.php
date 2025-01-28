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
        SET name = :name, model_id = :model_id, buy_price = :buy_price, buy_date = :buy_date, type = :type, fuel = :fuel, km = :km, cv = :cv, color = :color, transmission = :transmission, doors = :doors, seats = :seats
        WHERE id = :id'
        );

        $isUpdated = $stmt->execute(
            [
                ':name'         => $vehicle->getName(),
                ':model_id'     => $vehicle->getModelId(),
                ':buy_price'    => $vehicle->getBuyPrice(),
                ':buy_date'     => $vehicle->getBuyDate(),
                ':type'         => $vehicle->getType(),
                ':fuel'         => $vehicle->getFuel(),
                ':km'           => $vehicle->getKm(),
                ':cv'           => $vehicle->getCv(),
                ':color'        => $vehicle->getColor(),
                ':transmission' => $vehicle->getTransmission(),
                ':doors'        => $vehicle->getDoors(),
                ':seats'        => $vehicle->getSeats(),
                ':id'           => $vehicle->getId(),
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
            $stmt = $pdo->prepare(
                'INSERT INTO vehicles (name, model_id, buy_price, buy_date, type, fuel, km, cv, color, transmission, doors, seats) 
            VALUES (:name, :model_id, :buy_price, :buy_date, :type, :fuel, :km, :cv, :color, :transmission, :doors, :seats)'
            );
            $stmt->execute(
                [
                    ':name'         => $vehicle->getName(),
                    ':model_id'     => $vehicle->getModelId(),
                    ':buy_price'    => $vehicle->getBuyPrice(),
                    ':buy_date'     => $vehicle->getBuyDate(),
                    ':type'         => $vehicle->getType(),
                    ':fuel'         => $vehicle->getFuel(),
                    ':km'           => $vehicle->getKm(),
                    ':cv'           => $vehicle->getCv(),
                    ':color'        => $vehicle->getColor(),
                    ':transmission' => $vehicle->getTransmission(),
                    ':doors'        => $vehicle->getDoors(),
                    ':seats'        => $vehicle->getSeats(),
                ]
            );

            $insertedId = $pdo->lastInsertId();
            return $this->findOneById($insertedId);
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                return 'vehicle already exists';
            }

            return $e->getMessage();
        }
    }
}
