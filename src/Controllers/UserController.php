<?php

namespace Controllers;

use Core\ControllerInterface;
use Managers\UserManager;
use Core\Request;
use Core\Response;
use Core\Auth\Role;
use Entities\User;
use Core\Validator\Validator;
use Services\UserService;
use Core\Route;

/**
 * Class UserController
 * Controller for handling user-related actions.
 */
class UserController implements ControllerInterface
{
    /**
     * Handles the GET request with the optionnal param id.
     * Get a user by its ID or user list.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route(path: '/api/users/{:id}', method: 'GET', secure: Role::ADMIN)]
    public function get(Request $request, Response $response): Response
    {
        $id = $request->getParam('id');

        try {
            if (!$id) {
                $users = UserService::getAll();
                return $response->sendJson($users);
            }

            if (!Validator::isId($id)) {
                return $response->sendJson(
                    ['error' => 'Invalid id'],
                    Response::HTTP_BAD_REQUEST
                );
            }

            $user = UserService::getOneById($id);

            if ($user && $user instanceof User) {
                return $response->sendJson($user->toArray());
            } else {
                return $response->sendJson(
                    ['error' => 'User not found'],
                    Response::HTTP_BAD_REQUEST
                );
            }
        } catch (\Exception $e) {
            return $response->sendJson(
                ['error' => 'An error occurred'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }


    /**
     * Handles the PUT request with the dynamical param ID.
     * Replace a user by its ID.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route(path: '/api/users/{id}', method: 'PUT', secure: Role::ADMIN)]
    public function put(Request $request, Response $response): Response
    {
        $id = $request->getParam('id');

        if (!Validator::isId($id)) {
            return $response->sendJson(
                ['error' => 'Invalid id'],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $userManager = new UserManager();
            $user        = $userManager->findOneById($id);

            if ($user) {
                $data = $request->getJson();

                if (!$data || !UserService::isValid($data)) {
                    return $response->sendJson(
                        ['error' => 'Invalid data'],
                        Response::HTTP_BAD_REQUEST
                    );
                }

                $prepared = UserService::prepare($data, $user);

                $updated = $userManager->updateOne($prepared);

                if ($updated instanceof User) {
                    return $response->sendJson($updated->toArray());
                }
                return $response->sendJson(
                    ['error' => $updated],
                    Response::HTTP_BAD_REQUEST
                );
            } else {
                return $response->sendJson(
                    ['error' => 'User not found'],
                    Response::HTTP_BAD_REQUEST
                );
            }
        } catch (\Exception $e) {
            return $response->sendJson(
                ['error' => 'An error occurred' . $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }


    /**
     * Handles the PATCH request with the dynamical param ID.
     * Update a user by its ID.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route(path: '/api/users/{id}', method: 'PATCH', secure: Role::ADMIN)]
    public function patch(Request $request, Response $response): Response
    {
        $id = $request->getParam('id');

        if (!Validator::isId($id)) {
            return $response->sendJson(
                ['error' => 'Invalid user ID'],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $userManager = new UserManager();
            $user        = $userManager->findOneById($id);
            if ($user) {
                $data = $request->getJson();

                if (!$data || !UserService::isValid($data)) {
                    return $response->sendJson(
                        ['error' => 'Invalid data'],
                        Response::HTTP_BAD_REQUEST
                    );
                }

                $prepared = UserService::prepare($data, $user);
                $updated  = $userManager->updateOne($prepared);
                if ($updated instanceof User) {
                    return $response->sendJson($updated->toArray());
                }

                return $response->sendJson(
                    ['error' => $updated],
                    Response::HTTP_BAD_REQUEST
                );
            } else {
                return $response->sendJson(
                    ['error' => 'User not found'],
                    Response::HTTP_BAD_REQUEST
                );
            }
        } catch (\Exception $e) {
            return $response->sendJson(
                ['error' => 'An error occurred'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }


    /**
     * Handles the DELETE request with the dynamical param ID.
     * Delete a user by its ID.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route(path: '/api/users/{id}', method: 'DELETE', secure: Role::ADMIN)]
    public function delete(Request $request, Response $response): Response
    {
        $id = $request->get('id');

        if (!Validator::isId($id)) {
            return $response->sendJson(
                ['error' => 'Invalid user ID'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $userManager = new UserManager();

        try {
            $user = $userManager->findOneById($id);
            if ($user) {
                $deleted = $userManager->deleteOneById($user->getId());
                if ($deleted) {
                    return $response->send('', Response::HTTP_NO_CONTENT);
                }
            }

            // Si l'utilisateur n'est pas trouvé, on renvoie une erreur 404
            return $response->sendJson(
                ['error' => 'User not found'],
                Response::HTTP_NOT_FOUND
            );
        } catch (\Exception $e) {
            // Gérer les exceptions globales, si un autre problème se produit
            return $response->sendJson(
                ['error' => 'An error occurred'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }


    /**
     * Handles the POST request.
     * Create a user by its ID.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route(path: '/api/users', method: 'POST', secure: Role::ADMIN)]
    public function post(Request $request, Response $response): Response
    {
        try {
            $data = $request->getJson();

            if (!$data || !UserService::isValidPost($data)) {
                return $response->sendJson(
                    ['error' => 'Missing data'],
                    Response::HTTP_BAD_REQUEST
                );
            }
            $userManager = new UserManager();

            $prepared = UserService::prepare($data);

            $res = $userManager->insertOne($prepared);

            if ($res instanceof User) {
                return $response->sendJson($res->toArray());
            }

            return $response->sendJson(
                ['error' => $res],
                Response::HTTP_BAD_REQUEST
            );
        } catch (\Exception $e) {
            return $response->sendJson(
                ['error' => 'An error occurred'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
