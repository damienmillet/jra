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

// use Core\ControllerInterface;
use Core\Request;
use Core\Response;
use Core\View;
use Core\Route;

/**
 * Class ApiController
 * Controller for handling user-related actions.
 *
 * @category Controllers
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT Licenses
 * @link     damien-millet.dev
 */
class DocController
// implements ControllerInterface
// commenté pour ne pas avoir a implementer de méthodes inutiles
{
    /**
     * Handles the GET request.
     * Display the API documentation.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response The response object with the rendered view.
     */
    #[Route(path: '/api/doc', method: 'GET', secure: false)]
    public function get(Request $request, Response $response): Response
    {
        return $response->send(View::render('../../docs/index'), Response::HTTP_OK);
    }
}
