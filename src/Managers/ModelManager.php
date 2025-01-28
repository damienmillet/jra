<?php

/**
 * Manager file for defining the ModelManager class.
 * php version 8.2
 *
 * @category Managers
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Managers;

use Entities\Model;
use Core\BddManager;
use Services\ModelService;

/**
 * Class ModelManager
 *
 * @category Managers
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class ModelManager extends BddManager
{
    /**
     * Update model in database.
     *
     * @param Model $model The model object to update.
     *
     * @return Model|null The updated model object.
     */
    public function updateOne(Model $model): ?Model
    {
        $pdo  = $this->getPdo();
        $stmt = $pdo->prepare(
            'UPDATE models 
            SET name = :name, hash = :hash, roles = :roles
            WHERE id = :id'
        );

        $isUpdated = $stmt->execute(
            [
                ':name'  => $model->getName(),
                ':hash'  => $model->getHash(),
                ':roles' => ConvertService::arrayToJson($model->getRoles()),
                ':id'    => $model->getId(),
            ]
        );

        if (!$isUpdated) {
            return null;
        }

        // Récupération des données mises à jour
        $stmt = $pdo->prepare('SELECT * FROM models WHERE id = :id');
        $stmt->execute([':id' => $model->getId()]);

        return $stmt->fetchObject(Model::class) ?: null;
    }


    /**
     * Insert a new model into the database.
     *
     * @param Model $model The model object to insert.
     *
     * @return Model|null|string The inserted model object or null if insertion failed.
     */
    public function insertOne(Model $model): Model|string
    {
        $pdo = $this->getPdo();
        try {
            $exist = $this->exist('name', $model->getName());

            if ($exist) {
                return 'model name already exist';
            }

            $stmt = $pdo->prepare(
                'INSERT INTO models (name, hash, roles) 
                VALUES (:name, :hash, :roles)'
            );
            $stmt->execute(
                [
                    ':name'  => $model->getName(),
                    ':hash'  => $model->getHash(),
                    ':roles' => ConvertService::arrayToJson($model->getRoles()),
                ]
            );

            $insertedId = $pdo->lastInsertId();
            return $this->findOneById($insertedId);
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                return 'model name already exist';
            }

            return $e->getMessage();
        }
    }
}
