<?php

/**
 * Service file for defining the VehicleService class.
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
use Entities\Vehicle;
use Managers\VehicleManager;

/**
 * Class VehicleService
 *
 * @category Services
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class VehicleService
{
    /**
     * List all users.
     *
     * @return array The list of users.
     */
    public static function getAll(): array
    {
        $userManager = new VehicleManager();
        $users       = $userManager->findAll();
        return ConvertService::toObjectArray($users);
    }

    /**
     * Get a user by ID.
     *
     * @param int $id The ID of the user.
     *
     * @return User|null The user object or null if not found.
     */
    public static function getOneById(int $id): ?User
    {
        $userManager = new VehicleManager();
        return $userManager->findOneById($id);
    }

    
    /**
     * Prepare a vehicle object with the provided data.
     *
     * @param array  $data    The data to prepare the vehicle with.
     * @param Vehicle $vehicle The vehicle object to prepare.
     *
     * @return Vehicle The prepared vehicle object.
     */
    public static function prepare(
        array $data,
        Vehicle $vehicle = new Vehicle()
    ) {
        return $vehicle->hydrate($data);
    }
}
