<?php

/**
 * Controller file for handling user-related actions.
 * php version 8.2
 *
 * @category Controllers
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Controllers;

use Core\Auth\Role;
use Core\Request;
use Core\Response;
use Core\Route;
use Services\HistoricService;
use Core\Validator\Validator;

/**
 * Class HistoricController
 * Controller for handling user-related actions.
 *
 * @category Controllers
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class HistoricController
{
    /**
     * Handles the GET request.
     * Display the historic page.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route(path: '/api/historics/{:id}', method: 'GET', secure: Role::ADMIN)]
    public function get(Request $request, Response $response): Response
    {
        $id = $request->getParam('id');

        try {
            if (!$id) {
                $hist = HistoricService::getAll();
                return $response->sendJson($hist);
            }

            if (!Validator::isId($id)) {
                return $response->sendJson(
                    ['error' => 'Invalid id'],
                    Response::HTTP_BAD_REQUEST
                );
            }

            $userHist = HistoricService::findOneById($id);
            return $response->sendJson($userHist);
        } catch (\Exception $e) {
            return $response->sendJson(
                ['error' => 'An error occurred ' . $e->getMessage() ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}


// todo modifier pour rendre optionnel et attendre user_id ou contact_id
