<?php

/**
 * Core file for defining the Route class.
 * php version 8.2
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Core;

use Core\Auth\Role;

/**
 * Class Route
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
#[\Attribute]
class Route
{
    private string $_action;

    private string $_method;

    private string $_path;

    private bool|Role $_secure;

    private array $_params;

    private object $_controller;

    private string $_pattern;


    /**
     * Constructor for the Route class.
     *
     * @param string       $path   The path of the route.
     * @param string       $method The HTTP method of the route.
     * @param boolean|Role $secure The security level of the route.
     * @param array        $params The parameters of the route.
     */
    public function __construct(
        string $path,
        string $method = 'GET',
        bool|Role $secure = true,
        array $params = []
    ) {
        $this->setPath($path);
        $this->setMethod($method);
        $this->setAction($method);
        $this->setSecure($secure);
        $this->setParams($params);
        $this->setPattern($path);
    }


    /**
     * Get the path of the route.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->_path;
    }


    /**
     * Set the path of the route.
     *
     * @param string $path
     *
     * @return Route
     */
    public function setPath(string $path): Route
    {
        $this->_path = $path;
        return $this;
    }


    /**
     * Get the HTTP method of the route.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->_method;
    }


    /**
     * Set the HTTP method of the route.
     *
     * @param string $method
     *
     * @return Route
     */
    public function setMethod(string $method): Route
    {
        $this->_method = $method;
        return $this;
    }


    /**
     * Get the action of the route.
     *
     * @return string
     */
    public function getAction(): string
    {
        return $this->_action;
    }


    /**
     * Set the action of the route.
     *
     * @param string $method
     *
     * @return Route
     */
    public function setAction(string $method): Route
    {
        $this->_action = strtolower($method);
        return $this;
    }


    /**
     * Set the controller of the route.
     *
     * @param object $controller The controller object.
     *
     * @return Route
     */
    public function setController(object $controller): Route
    {
        $this->_controller = $controller;
        return $this;
    }


    /**
     * Get the controller of the route.
     *
     * @return object
     */
    public function getController(): object
    {
        return $this->_controller;
    }


    /**
     * Get the security level of the route.
     *
     * @return boolean|Role
     */
    public function getSecure(): bool|Role
    {
        return $this->_secure;
    }


    /**
     * Set the security level of the route.
     *
     * @param boolean|Role $secure
     *
     * @return Route
     */
    public function setSecure(bool|Role $secure): Route
    {
        $this->_secure = $secure;
        return $this;
    }


    /**
     * Get the parameters of the route.
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->_params;
    }


    /**
     * Set the parameters of the route.
     *
     * @param array $params
     *
     * @return Route
     */
    public function setParams(array $params): Route
    {
        $this->_params = $params;
        return $this;
    }


    /**
     * Set the path pattern of the route.
     *
     * @param string $path The path to set the pattern for.
     *
     * @return Route
     */
    public function setPattern(string $path): Route
    {
        $this->_pattern = rtrim($path, '/') . '(?:/)?';

        $this->_pattern = preg_replace_callback(
            '/\{(:?)([a-zA-Z0-9_]+)\}/',
            function ($matches) {
                return str_contains($matches[1], ':') ? '(?:(?P<' . $matches[2] . '>[^/]*))?'
                // Paramètre optionnel
                    : '(?P<' . $matches[2] . '>[^/]+)';
                // Paramètre obligatoire
            },
            $path
        );

        // Supprimer les doubles slashs dans le chemin
        $this->_pattern = preg_replace('#//+#', '/', $this->_pattern);

        // Assurer que l'URL fonctionne avec ou sans slash final
        if (!str_ends_with($this->_pattern, '/?')) {
            $this->_pattern = rtrim($this->_pattern, '/') . '(?:/)?';
        }

        return $this;
    }


    /**
     * Get the pattern of the route.
     *
     * @return string
     */
    public function getPattern(): string
    {
        return $this->_pattern;
    }


    /**
     * Check if the given path matches the route pattern.
     *
     * @param string $path   The path to match against the route pattern.
     * @param array  $params The parameters extracted from the path.
     *
     * @return boolean True if the path matches, false otherwise.
     */
    public function matches(string $path, array &$params): bool
    {
        if (preg_match('#^' . $this->getPattern() . '$#', $path, $matches)) {
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            return true;
        }

        return false;
    }
}
