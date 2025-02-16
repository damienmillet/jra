<?php

namespace Controllers;

use Core\Auth\Role;
use Core\Request;
use Core\Response;
use Managers\VehicleManager;
use Entities\Vehicle;
use Services\VehicleService;
use Core\Route;
use Core\Validator\Validator;

/**
 * Class VehicleController
 * Controller for handling vehicle-related actions.
 */
class VehicleController
{
    /**
     * Handles the GET request with the dynamical param ID.
     * Get a vehicle by its ID.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route(path: '/api/vehicles/{:id}', method: 'GET', secure: Role::ADMIN)]
    public function get(Request $request, Response $response): Response
    {
        $id = $request->getParam('id');

        try {
            if ($id === null) {
                $users = VehicleService::getAll();
                return $response->sendJson($users);
            }

            // check filter if integer or not
            if (!Validator::isId($id)) {
                return $response->sendJson(
                    ['error' => 'Invalid id'],
                    Response::HTTP_BAD_REQUEST
                );
            }

            $vehicle = VehicleService::getOneById($id);

            if ($vehicle !== null && $vehicle instanceof Vehicle) {
                return $response->sendJson($vehicle->toArray());
            } else {
                return $response->sendJson(
                    ['error' => 'Vehicle not found'],
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
     * Replace a vehicle by its ID.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route(path: '/api/vehicles/{id}', method: 'PUT', secure: Role::ADMIN)]
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
            $vehicleManager = new VehicleManager();
            $vehicle        = $vehicleManager->findOneById((int) $id);
            if ($vehicle !== null) {
                $json = $request->getJson();

                $requiredKeys = [
                    'name',
                    'model_id',
                    'buy_price',
                    'buy_date',
                    'type',
                    'fuel',
                    'km',
                    'cv',
                    'color',
                    'transmission',
                    'doors',
                    'seats',
                ];

                $jsonVars    = get_object_vars($json);
                $missingKeys = array_diff($requiredKeys, array_keys($jsonVars));


                if (empty($json) || !empty($missingKeys)) {
                    $response->sendJson(
                        ['error' => 'Missing data: ' . implode(', ', $missingKeys)],
                        Response::HTTP_BAD_REQUEST
                    );
                }

                $prepared = VehicleService::prepare($json);
                $updated  = $vehicleManager->updateOne($prepared);
                if ($updated instanceof Vehicle) {
                    return $response->sendJson($updated->toArray());
                }
                return $response->sendJson(
                    ['error' => 'An error occurred'],
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            } else {
                return $response->sendJson(
                    ['error' => 'Vehicle not found'],
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
     * Update a vehicle by its ID.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route(path: '/api/vehicles/{id}', method: 'PATCH', secure: Role::ADMIN)]
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
            $vehicleManager = new VehicleManager();
            $vehicle        = $vehicleManager->findOneById($id);
            if ($vehicle !== null) {
                $json = $request->getJson();

                $prepared = VehicleService::prepare($json, $vehicle);

                $updated = $vehicleManager->updateOne($prepared);

                if ($updated instanceof Vehicle) {
                    return $response->sendJson($updated->toArray());
                }
                return $response->sendJson(
                    ['error' => 'An error occurred'],
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            } else {
                return $response->sendJson(
                    ['error' => 'Vehicle not found'],
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
     * Delete a vehicle by its ID.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route(path: '/api/vehicles/{:id}', method: 'DELETE', secure: Role::ADMIN)]
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

        $vehicleManager = new VehicleManager();

        try {
            $vehicle = $vehicleManager->findOneById((int) $id);
            if ($vehicle !== null) {
                $deleted = $vehicleManager->deleteOneById($vehicle->getId());
                if (is_numeric($deleted)) {
                    return $response->send('', Response::HTTP_NO_CONTENT);
                }
            }

            // Si l'utilisateur n'est pas trouvé, on renvoie une erreur 404
            return $response->sendJson(
                ['error' => 'Vehicle not found'],
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
     * Create a vehicle by its ID.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route(path: '/api/vehicles', method: 'POST', secure: Role::ADMIN)]
    public function post(Request $request, Response $response): Response
    {
        try {
            $vehicleManager = new VehicleManager();
            $json           = $request->getJson();
            $prepared       = VehicleService::prepare($json);
            $res            = $vehicleManager->insertOne($prepared);

            if (is_string($res)) {
                return $response->sendJson(
                    ['error' => $res],
                    Response::HTTP_BAD_REQUEST
                );
            }

            // return the vehicle if $res is an object Vehicle
            if ($res instanceof Vehicle) {
                return $response->sendJson($res->toArray());
            }

            return $response->sendJson(
                ['error' => 'An error occurred '],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        } catch (\Exception $e) {
            if ($e->getCode() === '23000') {
                return $response->sendJson(
                    ['error' => 'Vehicle already exists'],
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $response->sendJson(
                ['error' => 'An error occurred'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
