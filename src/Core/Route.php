<?php

namespace Core;

use Core\Auth\Role;

/**
 * Class Route
 */
#[\Attribute]
class Route
{
    /**
     * The action of the route.
     *
     * @var string
     */
    private string $action;

    /**
     * The HTTP method of the route.
     *
     * @var string
     */
    private string $method;

    /**
     * The path of the route.
     *
     * @var string
     */
    private string $path;

    /**
     * Indicates if the route is secure or the role required.
     *
     * @var boolean|Role
     */
    private bool|Role $secure;

    /**
     * The parameters of the route.
     *
     * @var array<string,string>
     */
    private array $params;

    /**
     * The controller handling the route.
     *
     * @var object
     */
    private object $controller;

    /**
     * The pattern of the route.
     *
     * @var string
     */
    private string $pattern;


    /**
     * Constructor for the Route class.
     *
     * @param string               $path   The path of the route.
     * @param string               $method The HTTP method of the route.
     * @param boolean|Role         $secure The security level of the route.
     * @param array<string,string> $params The parameters of the route.
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
        return $this->path;
    }


    /**
     * Set the path of the route.
     *
     * @param string $path The path to set.
     *
     * @return Route The current instance.
     */
    public function setPath(string $path): Route
    {
        $this->path = $path;
        return $this;
    }


    /**
     * Get the HTTP method of the route.
     *
     * @return string The HTTP method of the route.
     */
    public function getMethod(): string
    {
        return $this->method;
    }


    /**
     * Set the HTTP method of the route.
     *
     * @param string $method The HTTP method to set.
     *
     * @return Route The current instance.
     */
    public function setMethod(string $method): Route
    {
        $this->method = $method;
        return $this;
    }


    /**
     * Get the action of the route.
     *
     * @return string The action of the route.
     */
    public function getAction(): string
    {
        return $this->action;
    }


    /**
     * Set the action of the route.
     *
     * @param string $method The method to set as action.
     *
     * @return Route
     */
    public function setAction(string $method): Route
    {
        $this->action = strtolower($method);
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
        $this->controller = $controller;
        return $this;
    }


    /**
     * Get the controller of the route.
     *
     * @return object
     */
    public function getController(): object
    {
        return $this->controller;
    }


    /**
     * Get the security level of the route.
     *
     * @return boolean|Role
     */
    public function getSecure(): bool|Role
    {
        return $this->secure;
    }


    /**
     * Set the security level of the route.
     *
     * @param boolean|Role $secure The security level to set.
     *
     * @return Route
     */
    public function setSecure(bool|Role $secure): Route
    {
        $this->secure = $secure;
        return $this;
    }


    /**
     * Get the parameters of the route.
     *
     * @return array<string,string>
     */
    public function getParams(): array
    {
        return $this->params;
    }


    /**
     * Set the parameters of the route.
     *
     * @param array<string,string> $params The parameters to set.
     *
     * @return Route
     */
    public function setParams(array $params): Route
    {
        $this->params = $params;
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
        $this->pattern = rtrim($path, '/') . '(?:/)?';

        $this->pattern = preg_replace_callback(
            '/\{(:?)([a-zA-Z0-9_]+)\}/',
            function (array $matches): string {
                return str_contains($matches[1], ':')
                    ? '(?:(?P<' . $matches[2] . '>[^/]*))?'
                    // Paramètre optionnel
                    : '(?P<' . $matches[2] . '>[^/]+)';
                // Paramètre obligatoire
            },
            $path
        );

        // Supprimer les doubles slashs dans le chemin
        $this->pattern = preg_replace('#//+#', '/', $this->pattern);

        // Assurer que l'URL fonctionne avec ou sans slash final
        if (!str_ends_with($this->pattern, '/?')) {
            $this->pattern = rtrim($this->pattern, '/') . '(?:/)?';
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
        return $this->pattern;
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
