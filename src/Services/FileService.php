<?php

/**
 * Service file for defining the FileService class.
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
use Entities\File;
use Managers\FileManager;

/**
 * Class FileService
 *
 * @category Services
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class FileService
{
    /**
     * List all users.
     *
     * @return array The list of users.
     */
    public static function getAll(): array
    {
        $userManager = new FileManager();
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
        $userManager = new FileManager();
        return $userManager->findOneById($id);
    }

    
    /**
     * Prepare a file object with the provided data.
     *
     * @param array $data The data to prepare the file with.
     * @param File  $file The file object to prepare.
     *
     * @return File The prepared file object.
     */
    public static function prepare(
        array $data,
        File $file = new File()
    ) {

        $data['path'] = $data['full_path'];
        $file = $file->hydrate($data);
        $content = file_get_contents($data['tmp_name']);
        $blob = base64_decode($content);
        $file->setBlob($blob);

        return $file;
    }
}
