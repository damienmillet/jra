<?php

/**
 * Controller file for handling user-related actions.
 * php version 8.2
 *
 * @category Controllers
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     https://damien-millet.dev
 */

namespace Controllers;

use Core\Auth\Role;
use Core\Request;
use Core\Response;
use Core\Route;
use Services\ConvertService;
use Managers\ContactManager;
use Entities\Contact;

/**
 * Class ExportController
 * Controller for handling user-related actions.
 *
 * @category Controllers
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     https://damien-millet.dev
 */
class ImportController
{
    /**
     * Handles the GET request.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route(path: '/api/import', method: 'POST', secure: Role::ADMIN)]
    public function get(Request $request, Response $response): Response
    {
        $operator = $request->getAuthData()['payload']['id'];

        // Récupération des données depuis le fichier CSV
        $csvData = ConvertService::fromCsv($request->getBody());

        if (empty($csvData)) {
            return $response->sendJson(['error' => 'No data found'], 400);
        }

        $contactManager = new ContactManager();

        // Récupérer tous les contacts existants et les stocker par ID pour un accès rapide
        $existingContacts = $contactManager->findAllByIds(array_column($csvData, 'id'));

        // Initialisation des listes pour les ajouts et mises à jour
        $contactsToAdd = [];
        $contactsToUpdate = [];

        // Traitement des données CSV
        foreach ($csvData as $data) {
            // Vérification si le contact existe déjà dans la base
            $contact = $existingContacts[$data['id']] ?? null;

            // Si le contact n'existe pas, on l'ajoute
            if (!$contact) {
                $contact = new Contact();
                $contactsToAdd[] = $contact->setName($data['name'])
                    ->setEmail($data['email']);
            } else {
                // Si le contact existe, on le met à jour
                $contactsToUpdate[] = $contact->setName($data['name'])
                    ->setEmail($data['email']);
            }
        }

        // Traitement des ajouts en une seule requête
        if (!empty($contactsToAdd)) {
            $contactsToAdded = $contactManager->insertMany($contactsToAdd);
        }

        // Traitement des mises à jour en une seule requête
        if (!empty($contactsToUpdate)) {
            $contactsToUpdated = $contactManager->updateMany($contactsToUpdate, $operator);
        }

        return $response->sendJson(
            [
                'Added' => count($contactsToAdd),
                'Updated' => count($contactsToUpdate)
            ]
        );
    }
}
