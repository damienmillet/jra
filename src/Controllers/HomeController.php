<?php

namespace Controllers;

use Core\Request;
use Core\Response;
use Core\Route;
use Core\View;

/**
 * Class HomeController
 * Controller for handling user-related actions.
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
