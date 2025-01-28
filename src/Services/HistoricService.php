<?php

/**
 * Service file for defining the UserService class.
 * php version 8.2
 *
 * @category Services
 * @package  Jra
 * @author   Damien Millet <historic@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Services;

use Managers\HistoricManager;
use Services\ConvertService;


/**
 * Class UserService
 *
 * @category Services
 * @package  Jra
 * @author   Damien Millet <historic@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class HistoricService
{
    /**
     * List all historics.
     *
     * @return array The list of Historics.
     */
    public static function getAll(): array
    {
        $historicManager = new HistoricManager();
        $historics = $historicManager->findAll();
        return ConvertService::toObjectArray($historics);
    }

    /**
     * Find a historic by its ID.
     *
     * @param integer $id The ID of the historic.
     *
     * @return array The historics found or null.
     */
    public static function findOneById(int $id): array
    {
        $historicManager = new HistoricManager();
        return $historicManager->findManyById($id);
    }
}
