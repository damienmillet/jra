<?php

/**
 * Manager file for defining the History class.
 * php version 8.2
 *
 * @category Managers
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Managers;

use Core\BddManager;

/**
 * Class HistoricManager
 *
 * @category Managers
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class HistoricManager  extends BddManager
{
    /**
     * Find many records by contact ID.
     *
     * @param int $id The contact ID.
     *
     * @return array The list of contact history records.
     */
    public function findManyById($id)
    {
        $pdo  = $this->getPdo();
        $sql = "SELECT ch.*, u.name AS modified_by_name
                FROM `historics` ch
                LEFT JOIN `users` u ON ch.modified_by = u.id
                WHERE ch.contact_id = :id
                ORDER BY ch.modified_at DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id'=> $id]);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
