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

use Core\Request;
use Core\Response;
use Core\Auth\Role;
use Core\Route;
use Entities\File;
use Core\Validator\Validator;
use Managers\FileManager;
use Services\FileService;

/**
 * Class FileController
 * Controller for handling user-related actions.
 *
 * @category Controllers
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     https://damien-millet.dev
 */
class FileController
{
    /**
     * Handles the POST request.
     *
     * @param mixed $request  The request object.
     * @param mixed $response The response object.
     *
     * @return Response
     */
    #[Route('/api/files/{id}', method: 'POST', secure: Role::ADMIN)]
    public function post(Request $request, Response $response): Response
    {
        $id = $request->getParam('id');

        if (!Validator::isId($id)) {
            return $response->sendJson(
                ['error' => 'Invalid id'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $files = $request->getFiles();

        foreach ($files as $file) {

            $prepared = FileService::prepare($file);

            $fileManager = new FileManager();

            $file = $fileManager->insertOne($prepared);

            var_dump($file);
            if (!$file instanceof File) {
                return $response->sendJson(
                    ['error' => 'Failed to save file'],
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            }
        }

        return $response->sendJson(
            ['success' => 'Files uploaded successfully'],
            Response::HTTP_OK
        );

    }


    /**
     * Handles the GET request.
     *
     * @param mixed $request  The request object.
     * @param mixed $response The response object.
     *
     * @return Response
     */
    #[Route('/api/files/{id}', method: 'GET', secure: Role::ADMIN)]
    public function get(Request $request, Response $response): Response
    {
        // si la ressource est deja liée à un utilisateur on la lie sans la dupliquer
        // si la ressource n'existe pas on la crée et on la lie à l'utilisateur
        $response->setStatusCode(200);
        // $response->setContent('ExportController');
        return $response;
    }


    /**
     * Handles the DELETE request.
     *
     * @param mixed $request  The request object.
     * @param mixed $response The response object.
     *
     * @return Response
     */
    #[Route('/api/files/{id}', method: 'DELETE', secure: Role::ADMIN)]
    public function delete(Request $request, Response $response): Response
    {
        $id = $request->getParam('id');
        if (!$id) {
            return $response->sendJson(
                ['error' => 'id needed'],
                Response::HTTP_NOT_FOUND
            );
        }

        // si la ressource est liée à un utilisateur on la supprime de l'utilisateur
        // si elle n'est plus liée a un utilisateur on la supprime de
        // la base de donnée
        $response->setStatusCode(200);
        // $response->setContent('ExportController');
        return $response;
    }
}
