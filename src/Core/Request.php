<?php

/**
 * Core file for defining the Request class.
 * php version 8.2
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Core;

/**
 * Class Request
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class Request
{
    private string $_method;

    private string $_uri;

    private array $_params = [];

    private array $_authData = [];

    // get $_SERVER
    // get $_GET
    // get $_POST
    // get $_FILES
    // get $_COOKIE
    // get $_SESSION
    // get $_ENV
    // getallheaders()


    /**
     * Request constructor.
     */
    public function __construct()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->setMethod($_SERVER['REQUEST_METHOD']);
        $this->setUri($uri);
    }


    /**
     * Setter for the HTTP method.
     *
     * @param string $method HTTP method
     *
     * @return Request
     */
    public function setMethod(string $method): Request
    {
        $this->_method = $method;
        return $this;
    }


    /**
     * Setter for the URI.
     *
     * @param string $uri URI of the request
     *
     * @return Request
     */
    public function setUri(string $uri): Request
    {
        $this->_uri = $uri;
        return $this;
    }


    /**
     * Set a parameter.
     *
     * @param string $key   The parameter key
     * @param mixed  $value The parameter value
     *
     * @return Request
     */
    public function setParam(string $key, $value): Request
    {
        $this->_params[$key] = $value;
        return $this;
    }


    /**
     * Get a parameter by key.
     *
     * @param string $key The parameter key
     *
     * @return mixed The parameter value or null if not found
     */
    public function getParam(string $key)
    {
        return $this->_params[$key] ?? null;
    }


    /**
     * Getter for the HTTP method.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->_method;
    }


    /**
     * Getter for the URI.
     *
     * @return string
     */
    public function getUri(): string
    {
        return $this->_uri;
    }


    /**
     * Summary of getJson
     *
     * @param boolean $assoc If true, returned objects will be converted
     *                       into associative arrays.
     *
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public function getJson(bool $assoc = true): mixed
    {
        $input = file_get_contents('php://input');

        if (isset($input)) {
            $data  = json_decode($input, $assoc);
        }

        // Vérification des erreurs JSON
        if (!$input && !$data && json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Invalid JSON: ' . json_last_error_msg());
        }

        return $data;
    }


    /**
     * Getter for the query string.
     *
     * @return array The query string converted to an associative array.
     */
    public function getQuery(): array
    {
        $headers = $this->getHeaders();
        return $this->_queryToArray($headers['QUERY_STRING']);
    }


    /**
     * Getter for the request headers.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        $headers = [];

        // getallheaders();
        foreach ($_SERVER as $key => $value) {
            $headers[$key] = $value;
        }

        return $headers;
    }


    /**
     * Getter for a specific request header.
     *
     * @param string $string The name of the header to retrieve.
     *
     * @return mixed The value of the header or null if not found.
     */
    public function getHeader(string $string): mixed
    {
        $headers = $this->getHeaders();
        if (isset($headers[$string])) {
            return $headers[$string];
        }

        return null;
    }


    /**
     * Summary of get
     *
     * @param string $key     The key to get.
     * @param mixed  $default The default value if the key is not found.
     * @param mixed  $filter  The filter to apply.
     *
     * @return mixed
     */
    public function get(string $key, $default = null, ?int $filter = 515): mixed
    {
        if (!isset($_GET[$key])) {
            return $default;
        }

        $value = $_GET[$key];

        if ($filter !== null) {
            $value = filter_var($value, $filter);
        }

        return ($value ?? $default);
    }


    /**
     * Setter for authentication data.
     *
     * @param array $authData Authentication data
     *
     * @return static
     */
    public function setAuthData(array $authData): static
    {
        $this->_authData = $authData;
        return $this;
    }


    /**
     * Getter for authentication data.
     *
     * @return array Authentication data
     */
    public function getAuthData(): array
    {
        return $this->_authData;
    }


    /**
     * Summary of post
     *
     * @param string $key     The key to get.
     * @param mixed  $default The default value if the key is not found.
     * @param mixed  $filter  The filter to apply.
     *
     * @return mixed
     */
    public function post(string $key, $default = null, ?int $filter = 515): mixed
    {
        if (!isset($_POST[$key])) {
            return $default;
        }

        $value = $_POST[$key];

        // Appliquer un filtre si spécifié
        if ($filter !== null) {
            $value = filter_var($value, $filter);
        }

        return ($value ?? $default);
    }


    /**
     * Converts a query string into an associative array.
     *
     * @param string $query The URL containing the query string.
     *
     * @return array The query string converted to an associative array.
     */
    private function _queryToArray(string $query): array
    {
        // Décoder les entités HTML
        $decodedQuery = html_entity_decode($query);
        // Convertir en tableau associatif
        $queryArray = [];
        parse_str($decodedQuery, $queryArray);
        return $queryArray;
    }

    /**
     * Getter for uploaded files.
     *
     * @return array The uploaded files.
     */
    public function getFiles(): array
    {
        return $_FILES;
    }

    /**
     * Getter for a specific cookie.
     *
     * @param string $key The name of the cookie to retrieve.
     *
     * @return mixed The value of the cookie or null if not found.
     */
    public function getCookie(string $key): mixed
    {
        return $_COOKIE[$key] ?? null;
    }

    /**
     * Getter for the request body.
     *
     * @return string The request body.
     */
    public function getBody(): string
    {
        return file_get_contents('php://input');
    }
}
