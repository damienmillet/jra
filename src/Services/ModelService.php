<?php

/**
 * Service file for defining the ModelService class.
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
use Entities\Model;
use Managers\ModelManager;

/**
 * Class ModelService
 *
 * @category Services
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class ModelService
{
    /**
     * List all users.
     *
     * @return array The list of users.
     */
    public static function getAll(): array
    {
        $userManager = new ModelManager();
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
        $userManager = new ModelManager();
        return $userManager->findOneById($id);
    }

    
    /**
     * Prepare a model object with the provided data.
     *
     * @param array $data  The data to prepare the model with.
     * @param Model $model The model object to prepare.
     *
     * @return Model The prepared model object.
     */
    public static function prepare(
        array $data,
        Model $model = new Model()
    ) {
        return $model->hydrate($data);
    }
}
