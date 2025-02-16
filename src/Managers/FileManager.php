<?php

namespace Managers;

use Entities\File;
use Core\BddManager;

/**
 * Class FileManager
 */
class FileManager extends BddManager
{
    /**
     * Update file in database.
     *
     * @param File $file The file object to update.
     *
     * @return File|null The updated file object.
     */
    public function updateOne(File $file): ?File
    {
        $pdo  = $this->getPdo();
        $stmt = $pdo->prepare(
            'UPDATE files 
            SET name = :name, type = :type, path = :path, blob = :blob
            WHERE id = :id'
        );

        $isUpdated = $stmt->execute(
            [
                ':name' => $file->getName(),
                ':type' => $file->getType(),
                ':path' => $file->getPath(),
                ':blob' => $file->getBlob(),
                ':id'   => $file->getId(),
            ]
        );

        if (!$isUpdated) {
            return null;
        }

        // Récupération des données mises à jour
        $stmt = $pdo->prepare('SELECT * FROM files WHERE id = :id');
        $stmt->execute([':id' => $file->getId()]);

        return $stmt->fetchObject(File::class) ?: null;
    }


    /**
     * Insert a new file into the database.
     *
     * @param File $file The file object to insert.
     *
     * @return File|null|string The inserted file object or null if insertion failed.
     */
    public function insertOne(File $file): File|string
    {
        $pdo = $this->getPdo();
        try {
            $exist = $this->exist('name', $file->getName());

            if ($exist) {
                return 'file name already exist';
            }

            $stmt = $pdo->prepare(
                'INSERT INTO files (name, type, path, blob) 
                VALUES (:name, :type, :path, :blob)'
            );
            $stmt->execute(
                [
                    ':name' => $file->getName(),
                    ':type' => $file->getType(),
                    ':path' => $file->getPath(),
                    ':blob' => $file->getBlob(),
                ]
            );

            return $pdo->lastInsertId();
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                return 'file name already exist';
            }

            return $e->getMessage();
        }
    }

    /**
     * Find a file by its ID.
     *
     * @param integer $id The ID of the file.
     *
     * @return File|null The file object or null if not found.
     */
    public function findOneById(int $id): ?File
    {
        $pdo  = $this->getPdo();
        $stmt = $pdo->prepare('SELECT * FROM files WHERE id = :id');
        $stmt->execute([':id' => $id]);

        return $stmt->fetchObject(File::class) ?: null;
    }

        /**
         * Check if a file exists by a specific field.
         *
         * @param string $field The field to check.
         * @param mixed  $value The value to check.
         *
         * @return boolean True if the file exists, false otherwise.
         */
    public function exist(string $field, $value): bool
    {
        $pdo  = $this->getPdo();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM files WHERE $field = :value");
        $stmt->execute([':value' => $value]);

        return $stmt->fetchColumn() > 0;
    }

    /**
     * Check if a file exists by contact ID.
     *
     * @param integer $contactId The contact ID to check.
     *
     * @return boolean True if a file exists for the contact, false otherwise.
     */
    public function existByContactId(int $contactId): bool
    {
        $pdo  = $this->getPdo();
        $stmt = $pdo->prepare(
            'SELECT COUNT(*) 
            FROM files f
            JOIN contact_files cf ON f.id = cf.file_id
            WHERE cf.contact_id = :contact_id'
        );
        $stmt->execute([':contact_id' => $contactId]);

        return $stmt->fetchColumn() > 0;
    }

    /**
     * Find all files by contact ID.
     *
     * @param integer $contactId The contact ID to find files for.
     *
     * @return array An array of File objects.
     */
    public function findAllByContactId(int $contactId): array
    {
        $pdo  = $this->getPdo();
        $stmt = $pdo->prepare(
            'SELECT f.* 
            FROM files f
            JOIN contact_files cf ON f.id = cf.file_id
            WHERE cf.contact_id = :contact_id'
        );
        $stmt->execute([':contact_id' => $contactId]);

        return $stmt->fetchAll(\PDO::FETCH_CLASS, File::class);
    }
}
