<?php

/**
 * Service file for defining the ExportService class.
 * php version 8.2
 *
 * @category Services
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Services;

use Managers\ContactManager;
use Services\ConvertService;

/**
 * Class ExportService
 *
 * @category Services
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class ImportService
{
    /**
     * Get a contacts with vehicle by kms.
     *
     * @param integer $kms      The kilometers of the vehicle.
     * @param string  $operator The comparison operator 
     *                          (e.g., "eq", "lt", "gt", "lte", "gte").
     *
     * @return array The list of Contacts.
     */
    public static function import(int $kms, string $operator = "eq"): array
    {
        $contactManager = new ContactManager();
        $contacts       = $contactManager->findAll();
        return ConvertService::toObjectArray($contacts);
        ;
    }
}
