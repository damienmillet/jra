<?php

namespace Controllers;

use Core\Auth\Role;
use Core\Route;
use Core\Request;
use Core\Response;
use Entities\Contact;
use Services\ContactService;
use Core\Validator\Validator;
use Managers\ContactManager;

/**
 * Class ContactController
 * Controller for handling contact-related actions.
 */
class ContactController
{
    /**
     * Handles the GET request with the dynamical param ID.
     * Get a contact by its ID.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route(path: '/api/contacts/{:id}', method: 'GET', secure: Role::USER)]
    public function get(Request $request, Response $response): Response
    {
        $id = $request->getParam('id');

        try {
            if (!$id) {
                $page  = $request->get('page');
                $limit = $request->get('limit');

                if ($page && !Validator::isInteger($page)) {
                    return $response->sendJson(
                        ['error' => 'page must be an integer'],
                        Response::HTTP_BAD_REQUEST
                    );
                }

                if ($limit && !Validator::isInteger($limit)) {
                    return $response->sendJson(
                        ['error' => 'limit must be an integer'],
                        Response::HTTP_BAD_REQUEST
                    );
                }

                if ($page && !$limit || !$page && $limit) {
                    return $response->sendJson(
                        ['error' => 'page and limit must be set together'],
                        Response::HTTP_BAD_REQUEST
                    );
                }

                if ($page && $limit) {
                    $offset   = (($page - 1) * $limit);
                    $contacts = ContactService::getPaginated($limit, $offset);
                    return $response->sendJson($contacts, Response::HTTP_OK);
                }

                $contacts = ContactService::getAll();
                return $response->sendJson($contacts, Response::HTTP_OK);
            }


            if (!Validator::isId($id)) {
                return $response->sendJson(
                    ['error' => 'Invalid contact id'],
                    Response::HTTP_BAD_REQUEST
                );
            }


            $contact = ContactService::findOneById($id);
            if ($contact && $contact instanceof Contact) {
                return $response->sendJson($contact->toArray(), Response::HTTP_OK);
            } else {
                return $response->sendJson(
                    ['error' => 'Contact not found'],
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
     * Handles the PUT request with the dynamical param ID.
     * Replace a contact by its ID.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route(path: '/api/contacts/{id}', method: 'PUT', secure: Role::ADMIN)]
    public function put(Request $request, Response $response): Response
    {
        $id       = $request->getParam('id');
        $operator = $request->getAuthData()['payload']['id'];

        if (!Validator::isId($id)) {
            return $response->sendJson(
                ['error' => 'Invalid format id : waiting for an integer'],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $contactManager = new ContactManager();
            $contact        = $contactManager->findOneById($id);
            if ($contact) {
                $json = $request->getJson();

                if (!$json || !ContactService::isValid($json)) {
                    return $response->sendJson(
                        ['error' => 'Invalid data'],
                        Response::HTTP_BAD_REQUEST
                    );
                }

                $prepared = ContactService::prepare($json, $contact);

                $updated = $contactManager->updateOne($prepared, $operator);


                if ($updated instanceof Contact) {
                    return $response->sendJson($updated->toArray());
                }
            } else {
                return $response->sendJson(
                    ['error' => 'Contact not found'],
                    Response::HTTP_BAD_REQUEST
                );
            }
        } catch (\Exception $e) {
            return $response->sendJson(
                ['error' => 'An error occurred'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $response->sendJson(
            ['error' => 'An error occurred '],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }


    /**
     * Handles the PATCH request with the dynamical param ID.
     * Update a contact by its ID.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route('/api/contacts/{id}', 'PATCH', Role::ADMIN)]
    public function patch(Request $request, Response $response): Response
    {
        $id       = $request->getParam('id');
        $operator = $request->getAuthData()['payload']['id'];

        if (!Validator::isId($id)) {
            return $response->sendJson(
                ['error' => 'Invalid contact ID'],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $contactManager = new ContactManager();
            $contact        = $contactManager->findOneById($id);
            if ($contact) {
                $json = $request->getJson();

                if (!$json || !ContactService::isValid($json)) {
                    return $response->sendJson(
                        ['error' => 'Invalid data'],
                        Response::HTTP_BAD_REQUEST
                    );
                }

                $prepared = ContactService::prepare($json, $contact);

                $updated = $contactManager->updateOne($prepared, $operator);

                if ($updated instanceof Contact) {
                    return $response->sendJson($updated->toArray());
                }
            } else {
                return $response->sendJson(
                    ['error' => 'Contact not found'],
                    Response::HTTP_BAD_REQUEST
                );
            }
        } catch (\Exception $e) {
            return $response->sendJson(
                ['error' => 'An error occurred' . $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $response->sendJson(
            ['error' => 'An error occurred '],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }


    /**
     * Handles the DELETE request with the dynamical param ID.
     * Delete a contact by its ID.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route(path: '/api/contacts/{id}', method: 'DELETE', secure: Role::ADMIN)]
    public function delete(Request $request, Response $response): Response
    {
        $id = $request->getParam('id');

        if (!Validator::isId($id)) {
            return $response->sendJson(
                ['error' => 'Invalid contact ID'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $contactManager = new ContactManager();

        try {
            $contact = $contactManager->findOneById($id);
            if ($contact) {
                $deleted = $contactManager->deleteOneById($contact->getId());
                if ($deleted) {
                    return $response->send('', Response::HTTP_NO_CONTENT);
                }
            }

            // Si l'utilisateur n'est pas trouvé, on renvoie une erreur 404
            return $response->sendJson(
                ['error' => 'Contact not found'],
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
     * Create a contact.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route(path: '/api/contact', method: 'POST', secure: Role::ADMIN)]
    public function post(Request $request, Response $response): Response
    {
        try {
            $contactManager = new ContactManager();
            $json           = $request->getJson();

            if (!$json || !ContactService::isValidPost($json)) {
                return $response->sendJson(
                    ['error' => 'Invalid data'],
                    Response::HTTP_BAD_REQUEST
                );
            }

            $prepared = ContactService::prepare($json);
            $res      = $contactManager->insertOne($prepared);

            // retourne le contact si $res est un Contact
            if ($res instanceof Contact) {
                return $response->sendJson($res->toArray());
            }
        } catch (\Exception $e) {
            if ($e->getCode() === '23000') {
                return $response->sendJson(
                    ['error' => 'Contact already exists'],
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $response->sendJson(
                ['error' => 'An error occurred ' . $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $response->sendJson(
            ['error' => 'An error occurred '],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
