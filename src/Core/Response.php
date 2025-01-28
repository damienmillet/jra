<?php

/**
 * Core file for defining the Response class.
 * php version 8.2
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Core;

use Core\Config;

/**
 * Class Response
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class Response
{
    public const HTTP_OK                    = 200;
    public const HTTP_CREATED               = 201;
    public const HTTP_NO_CONTENT            = 204;
    public const HTTP_MOVED_PERMANENTLY     = 301;
    public const HTTP_FOUND                 = 302;
    public const HTTP_NOT_MODIFIED          = 304;
    public const HTTP_BAD_REQUEST           = 400;
    public const HTTP_UNAUTHORIZED          = 401;
    public const HTTP_FORBIDDEN             = 403;
    public const HTTP_NOT_FOUND             = 404;
    public const HTTP_METHOD_NOT_ALLOWED    = 405;
    public const LENGTH_REQUIRED            = 411;
    public const PRECONDITION_FAILED        = 412;
    public const PAYLOAD_TOO_LARGE          = 413;
    public const URL_TOO_LONG               = 414;
    public const UNSUPPORTED_MEDIA_TYPE     = 415;
    public const RANGE_NOT_SATISFIABLE      = 416;
    public const IM_A_TEAPOT                = 418;
    public const TOO_MANY_REQUESTS          = 429;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;


    /**
     * Send a JSON response.
     *
     * @param array   $data       The data to be encoded as JSON.
     * @param integer $statusCode The HTTP status code for the response.
     *
     * @return Response
     */
    public function sendJson(
        array $data,
        int $statusCode = self::HTTP_OK
    ): Response {
        header('Content-Type: application/json');
        // $this->setCorsHeaders();
        return $this->send(json_encode($data), $statusCode);
    }


    /**
     * Set the HTTP status code for the response.
     *
     * @param integer $statusCode The HTTP status code.
     *
     * @return Response
     */
    public function setStatusCode(int $statusCode): Response
    {
        http_response_code($statusCode);
        return $this;
    }


    /**
     * Send the body of the response.
     *
     * @param string  $body       The body content.
     * @param integer $statusCode The HTTP status code for the response.
     *
     * @return Response
     */
    public function send(string $body, int $statusCode = self::HTTP_OK): static
    {
        // $this->setCorsHeaders();
        $this->setStatusCode($statusCode);
        echo $body;
        return $this;
    }


    /**
     * Set a header for the response.
     *
     * @param string $name  The name of the header.
     * @param string $value The value of the header.
     *
     * @return static
     */
    public function setHeader(string $name, string $value): static
    {
        header($name . ': ' . $value);
        return $this;
    }


    /**
     * Set CORS headers for the response.
     *
     * @return void
     */
    public static function setCorsHeaders()
    {
        $config  = Config::getInstance();
        $allowed = $config->get('ALLOWED_ORIGIN', '*');

        if (!in_array($allowed, $allowed)) {
            $allowed = '*';
        }

        header('Access-Control-Allow-Origin: ' . $allowed);
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE');
        header(
            'Access-Control-Allow-Headers: Authorization, Content-Type, X-CSRF-Token'
        );
        header('Access-Control-Allow-Credentials: true');
    }


    /**
     * Redirect the user to a new URL.
     *
     * @param string  $url        The URL to redirect to.
     * @param integer $statusCode The HTTP status code for the response.
     *
     * @return void
     */
    public function redirect(
        string $url,
        int $statusCode = self::HTTP_MOVED_PERMANENTLY
    ): Response {
        header('Location: ' . $url);
        return $this->send(json_encode(['url' => $url]), $statusCode);
    }
}
