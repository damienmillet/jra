<?php

namespace Controllers;

use Core\Auth\Role;
use Core\BddManager;
use Core\Request;
use Core\Response;
use Core\Route;

/**
 * Class StatController
 * Controller for handling user-related actions.
 */
class StatController
{
    /**
     * Handles the GET request.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route(path: '/api/stats', method: 'GET', secure: Role::USER)]
    public function get(Request $request, Response $response): Response
    {
        // UNION est plus performant que deux requêtes séparées
        // car il n'y a qu'une seule requête SQL à exécuter.
        // Dans ma situation je veux en faire un json,
        // je n'ai donc aucune utilité a l'utiliser
        // car je peux directement executer deux requêtes
        // en une seule via les sous-requetes ;).
        // en revanche,
        // si il y avait plus de 10 tables à interroger UNION serait plus rapide.
        // actuellement le gain est de l'ordre de 20% à 30%.
        // UNION n'est donc pas adapté.
        $pdo   = new BddManager();
        $pdo   = $pdo->getPdo();
        $query = $pdo->prepare(
            'SELECT 
            (SELECT COUNT(*) FROM contacts) AS total_contacts,
            (SELECT COUNT(*) FROM vehicles) AS total_vehicles;'
        );
        $query->execute();
        $data = $query->fetchAll();

        return $response->sendJson($data);
    }
}
