<?php

namespace Managers;

use Core\BddManager;

/**
 * Class HistoricManager
 */
class HistoricManager extends BddManager
{
    /**
     * Find many records by contact ID.
     *
     * @param integer $id The contact ID.
     *
     * @return array The list of contact history records.
     */
    public function findManyById($id)
    {
        $pdo = $this->getPdo();
        $sql = 'SELECT ch.*, u.name AS modified_by_name
                FROM `historics` ch
                LEFT JOIN `users` u ON ch.modified_by = u.id
                WHERE ch.contact_id = :id
                ORDER BY ch.modified_at DESC';

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
