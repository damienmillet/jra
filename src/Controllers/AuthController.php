<?php

namespace Controllers;

use Core\Auth\Role;
use Core\Request;
use Core\Response;
use Core\Route;
use Services\AuthService;
use Core\Validator\Validator;

/**
 * Class AuthController
 * Controller for handling user-related actions.
 */
class AuthController
{
    /**
     * Handles the GET request.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route(path: '/api/auth/csrf', method: 'GET', secure: Role::USER)]
    public function get(Request $request, Response $response): Response
    {
        $csrf = AuthService::csrfFactory();
        // reste a la stocker dans la session de l'utilisateur
        // ici nous n'avons pas de session pour le moment
        // pour le moment, il a juste le merite d'exister
        return $response->sendJson(['csrf_token' => $csrf], Response::HTTP_OK);
    }


    /**
     * Handles the POST request.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route(path: '/api/auth', method: 'POST', secure: false)]
    public function post(Request $request, Response $response): Response
    {
        $json = $request->getJson();

        if (Validator::isEmptyArray($json) || !AuthService::isValid($json)) {
            return $response->sendJson(
                ['error' => 'Invalid data'],
                Response::HTTP_BAD_REQUEST
            );
        }

        if (is_string($json['username']) && is_string($json['password'])) {
            $username = $json['username'];
            $password = $json['password'];
        } else {
            return $response->sendJson(
                ['error' => 'Invalid data'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $token = AuthService::login($username, $password);

        if ($token === false) {
            return $response->sendJson(['token' => $token], Response::HTTP_OK);
        }

        // Ajout d'un délai pour simuler un traitement long (1 seconde)
        // permettant de compliquer les attaques par force brute.
        sleep(1);

        return $response->sendJson(
            ['error' => 'Invalid credentials'],
            Response::HTTP_UNAUTHORIZED
        );
    }

    /**
     * Handles the token refresh request.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route(path: '/api/auth/refresh', method: 'GET', secure: Role::USER)]
    public function refreshToken(Request $request, Response $response): Response
    {
        $token = $request->getHeader('Authorization');

        if ($token === null) {
            return $response->sendJson(
                ['error' => 'Invalid token'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        if (!is_string($token)) {
            return $response->sendJson(
                ['error' => 'Invalid token'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $token = str_replace('Bearer ', '', $token);

        AuthService::refreshToken($token);

        return $response->sendJson(['token' => $token], Response::HTTP_OK);
    }
}
