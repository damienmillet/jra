<?php

/**
 * Manager file for defining the Model Contact class.
 * php version 8.2
 *
 * @category Managers
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Managers;

use Entities\Contact;
use Core\BddManager;

/**
 * Class ContactManager
 *
 * @category Managers
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class ContactManager extends BddManager
{
    /**
     * Update an existing contact in the database.
     *
     * @param Contact $contact  The contact object to update.
     * @param int     $operator modified by.
     *
     * @return Contact|string The updated contact object or null if update failed.
     */
    public function updateOne(Contact $contact, int $operator): Contact|string
    {
        try {
            $pdo  = $this->getPdo();

            $pdo->beginTransaction();

            $stmt = $pdo->prepare("UPDATE contacts SET name = :name, email = :email, modified_by = :modified_by WHERE id = :id");

            $isUpdated = $stmt->execute(
                [
                    ':name' => $contact->getName(),
                    ':email' => $contact->getEmail(),
                    ':modified_by' => $operator,  // L'ID de l'utilisateur modifiant le contact
                    ':id' => $contact->getId(),
                ]
            );

            if (!$isUpdated) {
                $pdo->rollBack();
                return null;
            }

            // Validation des modifications
            $pdo->commit();

            // Récupération des données mises à jour
            return $this->findOneById($contact->getId());
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                return 'user name already exist';
            }

            return $e->getMessage();
        }
    }

    /**
     * Find all contacts with pagination.
     *
     * @param int $limit  The number of contacts to retrieve.
     * @param int $offset The offset for pagination.
     *
     * @return array The array of contacts.
     */
    public function findAllPaginated(int $limit, int $offset): array
    {
        $pdo = $this->getPdo();
        $stmt = $pdo->prepare('SELECT * FROM contacts LIMIT :limit OFFSET :offset');

        // Exécution de la requête
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Find all contacts by their IDs.
     *
     * @param array $ids The array of contact IDs.
     *
     * @return array The array of contacts.
     */
    public function findAllByIds(array $ids): array
    {
        $pdo = $this->getPdo();
        $idsPlaceholder = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("SELECT * FROM contacts WHERE id IN ($idsPlaceholder)");

        $stmt->execute($ids);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    /**
     * Insert a new contact into the database.
     *
     * @param Contact $contact The contact object to insert.
     *
     * @return Contact|string The inserted contact object or string
     *                        if insertion failed.
     */
    public function insertOne(Contact $contact): Contact|string
    {
        $pdo = $this->getPdo();
        try {
            $exist = $this->exist('email', $contact->getEmail());

            if ($exist) {
                return 'user email already exist';
            }

            $stmt = $pdo->prepare(
                'INSERT INTO contacts (name, email) 
                VALUES (:name, :email)'
            );
            $stmt->execute(
                [
                    ':name'  => $contact->getName(),
                    ':email' => $contact->getEmail(),
                ]
            );

            $insertedId = $pdo->lastInsertId();
            return $this->findOneById($insertedId);
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                return 'contact mail already exist';
            }

            return null;
        }
    }

    /**
     * Insert multiple contacts into the database.
     *
     * @param array $contacts The array of contact data to insert.
     *
     * @return int
     */
    public function insertMany(array $contacts): int
    {
        $pdo = $this->getPdo();

        // Construction de la requête d'insertion multiple
        $query = "INSERT INTO contacts (name, email, id) VALUES ";
        $placeholders = [];
        $values = [];

        foreach ($contacts as $contact) {
            $placeholders[] = "(?, ?, ?)";
            $values[] = $contact['name'];
            $values[] = $contact['email'];
            $values[] = $contact['id'];
        }

        $query .= implode(", ", $placeholders);

        // Exécution de la requête
        $stmt = $pdo->prepare($query);
        $stmt->execute($values);

        return count($contacts);
    }


    /**
     * Update multiple contacts in the database.
     *
     * @param array $contacts The array of contact data to update.
     * @param int   $operator The ID of the user modifying the contacts.
     *
     * @return int
     */
    public function updateMany(array $contacts, int $operator): int
    {
        $pdo = $this->getPdo();

        // Construction de la requête d'update avec un CASE pour chaque colonne à mettre à jour
        $query = "UPDATE contacts SET name = CASE ";
        $query .= "email = CASE ";

        $ids = [];
        $nameValues = [];
        $emailValues = [];

        // Construction des tableaux de valeurs pour la mise à jour
        foreach ($contacts as $contact) {
            $ids[] = $contact['id'];
            $nameValues[] = "WHEN id = ? THEN ?";
            $emailValues[] = "WHEN id = ? THEN ?";
        }

        // Ajout des conditions pour chaque id
        $query .= implode(" ", $nameValues) . " END, ";
        $query .= implode(" ", $emailValues) . " END, ";
        $query .= "modified_by = ? WHERE id IN (" . implode(",", array_fill(0, count($ids), "?")) . ")";

        // Fusionner les valeurs
        $values = array_merge(...array_map(fn($contact) => [$contact['id'], $contact['name']], $contacts));
        $values = array_merge($values, array_map(fn($contact) => [$contact['id'], $contact['email']], $contacts));
        $values[] = $operator;
        $values = array_merge($values, $ids);

        // Exécution de la requête
        $stmt = $pdo->prepare($query);
        $stmt->execute($values);

        return count($contacts);
    }

    /**
     * Find contacts by vehicle kilometers condition.
     *
     * @param int    $kms      The kilometers to compare.
     * @param string $operator The comparison operator (default is "eq").
     *
     * @return array The array of contacts.
     */
    public function findByKmsCondition(int $kms, string $operator = "eq"): array
    {
        // Construire la condition SQL en fonction de l'opérateur
        $condition = $this->getCondition($operator);

        // Préparer la requête SQL
        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM vehicles v JOIN contacts c ON v.contact_id = c.id WHERE v.km $condition :kms");
        $stmt->bindValue("kms", $kms, \PDO::PARAM_INT);
        $stmt->execute();

        // Récupérer les résultats
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Find contacts by vehicle years condition.
     *
     * @param int    $years    The years to compare.
     * @param string $operator The comparison operator (default is "eq").
     *
     * @return array The array of contacts.
     */
    public function findByYearsCondition(int $years, string $operator = "eq"): array
    {
        // Construire la condition SQL en fonction de l'opérateur
        $condition = $this->getCondition($operator);

        // Préparer la requête SQL
        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM vehicles v JOIN contacts c ON v.contact_id = c.id WHERE v.release_date $condition :years");
        $stmt->bindValue("years", $years, \PDO::PARAM_INT);
        $stmt->execute();

        // Récupérer les résultats
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Find contacts without associated vehicle.
     *
     * @return array The array of contacts without vehicles.
     */
    public function findWithoutVehicle(): array
    {
        // Préparer la requête SQL
        $pdo = $this->getPdo();
        $stmt = $pdo->prepare(
            "SELECT c.* 
                    FROM contacts c
                    LEFT JOIN vehicles v ON c.id = v.contact_id
                    WHERE v.contact_id IS NULL"
        );

        // Exécuter la requête
        $stmt->execute();

        // Récupérer et retourner les résultats
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findBy()
    {
        //         SELECT 
        //     contacts.id AS contact_id,
        //     contacts.nom AS contact_nom,
        //     contacts.email AS contact_email,
        //     vehicules.immatriculation AS vehicule_immatriculation,
        //     vehicules.date_mise_en_circulation AS date_circulation
        // FROM 
        //     contacts
        // INNER JOIN 
        //     vehicules ON contacts.id = vehicules.contact_id
        // WHERE 
        //     DATEDIFF(CURDATE(), vehicules.date_mise_en_circulation) > 3 * 365;
    }

    //     SELECT 
    //     contacts.id AS contact_id,
    //     contacts.nom AS contact_nom,
    //     contacts.email AS contact_email,
    //     vehicules.immatriculation AS vehicule_immatriculation,
    //     vehicules.kilometrage AS vehicule_kilometrage
    // FROM 
    //     contacts
    // INNER JOIN 
    //     vehicules ON contacts.id = vehicules.contact_id
    // WHERE 
    //     vehicules.kilometrage > 30000;
}
