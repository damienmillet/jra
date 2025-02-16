<?php

namespace Services;

use Core\Validator\ValidatorFactory;
use Entities\Contact;
use Managers\ContactManager;
use Services\ConvertService;

/**
 * Class UserService
 */
class ContactService
{
    /**
     * List all contacts.
     *
     * @return array The list of Contacts.
     */
    public static function getAll(): array
    {
        $contactManager = new ContactManager();
        $contacts       = $contactManager->findAll();
        return ConvertService::toObjectArray($contacts);
    }

    /**
     * Get paginated list of contacts.
     *
     * @param integer $limit The number of contacts per page.
     * @param integer $offet The page number.
     *
     * @return array The paginated list of contacts.
     */
    public static function getPaginated(int $limit, int $offet): array
    {
        $contactManager = new ContactManager();
        $contacts       = $contactManager->findAllPaginated($limit, $offet);
        return $contacts; // ConvertService::toObjectArray($contacts);
    }

    /**
     * Find a contact by its ID.
     *
     * @param integer $id The ID of the contact.
     *
     * @return Contact|null The contact found or null.
     */
    public static function findOneById(int $id): ?Contact
    {
        $contactManager = new ContactManager();
        return $contactManager->findOneById($id);
    }

    /**
     * Prepare a contact with the provided data.
     *
     * @param array   $data    The data to prepare the contact with.
     * @param Contact $contact The contact to prepare.
     *
     * @return Contact The prepared contact.
     */
    public static function prepare(
        array $data,
        Contact $contact = new Contact()
    ) {
        return $contact->hydrate($data);
    }

    /**
     * Validate the provided data.
     *
     * @param array $data The data to validate.
     *
     * @return boolean True if the data is valid, false otherwise.
     */
    public static function isValid(&$data): bool
    {
        $schema = [
            'name'  => [
                'type'     => 'string',
                'sanitize' => 'string',
                'required' => false,
            ],
            'email' => [
                'type'     => 'string',
                'sanitize' => 'email',
                'required' => false,
            ],
        ];

        return ValidatorFactory::validate($data, $schema);
    }

    /**
     * Validate the provided data.
     *
     * @param array $data The data to validate.
     *
     * @return boolean True if the data is valid, false otherwise.
     */
    public static function isValidPost(&$data): bool
    {
        $schema = [
            'name'  => [
                'type'     => 'string',
                'sanitize' => 'string',
                'required' => true,
            ],
            'email' => [
                'type'     => 'string',
                'sanitize' => 'email',
                'required' => true,
            ],
        ];

        return ValidatorFactory::validate($data, $schema);
    }
}
