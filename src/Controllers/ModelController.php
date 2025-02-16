<?php

namespace Controllers;

use Core\Auth\Role;
use Core\Request;
use Core\Response;
use Managers\ModelManager;
use Entities\Model;
use Services\ModelService;
use Core\Route;
use Core\Validator\Validator;

/**
 * Class ModelController
 * Controller for handling model-related actions.
 */
class ModelController
{
    /**
     * Handles the GET request with the dynamical param ID.
     * Get a model by its ID.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route(path: '/api/models/{:id}', method: 'GET', secure: Role::ADMIN)]
    public function get(Request $request, Response $response): Response
    {
        $id = $request->getParam('id');

        try {
            if ($id === null) {
                $users = ModelService::getAll();
                return $response->sendJson($users);
            }

            // check filter if integer or not
            if (!Validator::isId($id)) {
                return $response->sendJson(
                    ['error' => 'Invalid id'],
                    Response::HTTP_BAD_REQUEST
                );
            }

            $model = ModelService::getOneById($id);

            if ($model && $model instanceof Model) {
                return $response->sendJson($model->toArray());
            } else {
                return $response->sendJson(
                    ['error' => 'Model not found'],
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
     * Replace a model by its ID.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route(path: '/api/models/{id}', method: 'PUT', secure: Role::ADMIN)]
    public function put(Request $request, Response $response): Response
    {
        $id = $request->getParam('id');

        // check filter if integer or not
        if (!Validator::isId($id)) {
            return $response->sendJson(
                ['error' => 'Invalid or missing id'],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $modelManager = new ModelManager();
            $model        = $modelManager->findOneById((int) $id);
            if ($model) {
                $json = $request->getJson();

                $requiredKeys = [
                    'name',
                    'brand',
                    'model',
                    'version',
                    'year',
                    'price',
                    'category',
                ];

                $jsonVars    = get_object_vars($json);
                $missingKeys = array_diff($requiredKeys, array_keys($jsonVars));

                if (empty($json) || !empty($missingKeys)) {
                    return $response->sendJson(
                        ['error' => 'Missing data: ' . implode(', ', $missingKeys)],
                        Response::HTTP_BAD_REQUEST
                    );
                }

                $prepared = ModelService::prepare($json, $model);

                $updated = $modelManager->updateOne($prepared);

                if ($updated instanceof Model) {
                    return $response->sendJson($updated->toArray());
                }
                return $response->sendJson(
                    ['error' => 'An error occurred'],
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            } else {
                return $response->sendJson(
                    ['error' => 'Model not found'],
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
     * Handles the PATCH request with the dynamical param ID.
     * Update a model by its ID.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route(path: '/api/models/{id}', method: 'PATCH', secure: Role::ADMIN)]
    public function patch(Request $request, Response $response): Response
    {
        $id = $request->getParam('id');

        // check filter if integer or not
        if (!Validator::isId($id)) {
            return $response->sendJson(
                ['error' => 'Invalid or missing id'],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $modelManager = new ModelManager();
            $model        = $modelManager->findOneById($id);
            if ($model) {
                $json     = $request->getJson();
                $prepared = ModelService::prepare($json, $model);
                $updated  = $modelManager->updateOne($prepared);
                if ($updated instanceof Model) {
                    return $response->sendJson($updated->toArray());
                }
                return $response->sendJson(
                    ['error' => 'An error occurred'],
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            } else {
                return $response->sendJson(
                    ['error' => 'Model not found'],
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
     * Delete a model by its ID.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route(path: '/api/models/{:id}', method: 'DELETE', secure: Role::ADMIN)]
    public function delete(Request $request, Response $response): Response
    {
        $id = $request->getParam('id');

        // check filter if integer or not
        if (!Validator::isId($id)) {
            return $response->sendJson(
                ['error' => 'Invalid or missing id'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $modelManager = new ModelManager();

        try {
            $model = $modelManager->findOneById((int) $id);
            if ($model) {
                $deleted = $modelManager->deleteOneById($model->getId());
                if ($deleted) {
                    return $response->send('', Response::HTTP_NO_CONTENT);
                }
            }

            // Si l'utilisateur n'est pas trouvé, on renvoie une erreur 404
            return $response->sendJson(
                ['error' => 'Model not found'],
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
     * Create a model by its ID.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route(path: '/api/models', method: 'POST', secure: Role::ADMIN)]
    public function post(Request $request, Response $response): Response
    {
        try {
            $modelManager = new ModelManager();

            $json = $request->getJson();

            $prepared = ModelService::prepare($json);

            $res = $modelManager->insertOne($prepared);

            if ($res instanceof Model) {
                return $response->sendJson($res->toArray());
            }

            return $response->sendJson(
                ['error' => $res],
                Response::HTTP_BAD_REQUEST
            );
        } catch (\Exception $e) {
            if ($e->getCode() === '23000') {
                return $response->sendJson(
                    ['error' => 'Model already exists'],
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $response->sendJson(
                ['error' => 'An error occurred '],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
