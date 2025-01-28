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

use Core\Request;
use Core\Response;
use Core\Route;
use Core\View;

/**
 * Class HomeController
 * Controller for handling user-related actions.
 *
 * @category Controllers
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class HomeController
{
    /**
     * Handles the GET request.
     * Display the home page.
     *
     * @param Request  $request  Request object
     * @param Response $response Response object
     *
     * @return Response
     */
    #[Route(path: '/', method: 'GET', secure: false)]
    public function get(Request $request, Response $response): Response
    {
        return $response->send(View::render('documentation'), Response::HTTP_OK);
    }
}
