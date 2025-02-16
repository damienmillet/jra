<?php

namespace Services;

use Managers\ContactManager;
use Services\ConvertService;

/**
 * Class ImportService
 *
 * This class is responsible for handling the import operations.
 * It provides methods to import data from various sources and formats.
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
    public static function import(int $kms, string $operator = 'eq'): array
    {
        $contactManager = new ContactManager();
        $contacts       = $contactManager->findAll();
        return ConvertService::toObjectArray($contacts);
    }
}
