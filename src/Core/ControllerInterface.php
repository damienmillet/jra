<?php

namespace Core;

/**
 * Class ControllerInterface
 */
interface ControllerInterface
{
    /**
     * Retrieve an item.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function get(Request $request, Response $response): Response;


    /**
     * Create an item.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function post(Request $request, Response $response): Response;


    /**
     * Replace an item.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function put(Request $request, Response $response): Response;


    /**
     * Update an item.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function patch(Request $request, Response $response): Response;


    /**
     * Delete an item.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function delete(Request $request, Response $response): Response;
}
