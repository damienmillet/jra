<?php

namespace Core;

use Core\Request;
use Core\Response;
use Middlewares\AuthMiddleware;
use Core\Log\Logger;
use Core\Route;
use Exception;

/**
 * Class Router
 */
class Router
{
    /**
     * @var array<Route> The registered routes.
     */
    private array $routes = [];

    /**
     * @var Logger The logger instance.
     */
    private Logger $logger;


    /**
     * Router constructor.
     *
     * @param Logger $logger Logger instance.
     */
    public function __construct(Logger $logger)
    {
        $this->logger         = $logger;
        $controllersNamespace = 'Controllers';
        $controllersDirectory = __DIR__ . '/../Controllers';
        $this->registerAllControllers($controllersNamespace, $controllersDirectory);
    }


    /**
     * Get all registered routes.
     *
     * @return array<Route> The registered routes.
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }


    /**
     * Register routes from the given controller.
     *
     * @param object $controller The controller instance.
     *
     * @return void
     */
    private function registerRoutes(object $controller): void
    {
        $reflection = new \ReflectionClass($controller);

        foreach ($reflection->getMethods() as $method) {
            foreach ($method->getAttributes(Route::class) as $attribute) {
                $route = $attribute->newInstance();
                $route->setController($controller);
                $this->routes[] = $route;
            }
        }
    }


    /**
     * Register all controllers in the Controllers namespace.
     *
     * @param string $namespace The namespace of the controllers.
     * @param string $directory The directory of the controllers.
     *
     * @return void
     */
    private function registerAllControllers(
        string $namespace,
        string $directory
    ): void {
        foreach (glob($directory . '/*.php') as $filename) {
            $className     = basename($filename, '.php');
            $fullClassName = $namespace . '\\' . $className;

            if (class_exists($fullClassName)) {
                $controller = new $fullClassName();
                $this->registerRoutes($controller);
            }
        }
    }


    /**
     * Handle the incoming request and send the appropriate response.
     *
     * @param Request  $request  The incoming request.
     * @param Response $response The response to send.
     *
     * @return void
     */
    public function handle(Request $request, Response $response): void
    {
        $method = $request->getMethod();
        $uri    = rtrim($request->getUri(), '/') . '/';

        foreach ($this->getRoutes() as $route) {
            if ($route->getMethod() !== $method) {
                continue;
            }

            $params = [];

            if ($route->matches($uri, $params)) {
                foreach ($params as $key => $value) {
                    $request->setParam($key, $value);
                }

                $authData = [];

                if (
                    $route->getSecure()
                    && !AuthMiddleware::handle(
                        $request,
                        $response,
                        $route->getSecure()
                    )
                ) {
                    $response->sendJson(
                        ['error' => 'Unauthorized'],
                        Response::HTTP_UNAUTHORIZED
                    );
                    die();
                }

                $this->logger->log("$method $uri", 'info');
                $this->dispatch($route, [$request, $response]);
                return;
            }
        }

        $this->logger->log("Route not found: $method $uri", 'error');
        $response->sendJson(
            ['error' => 'Route not found'],
            Response::HTTP_NOT_FOUND
        );
        die();
    }


    /**
     * Dispatch the route to the appropriate controller and method.
     *
     * @param Route                $route  The route to dispatch.
     * @param array<string,string> $params The parameters to pass to the controller method.
     *
     * @return void
     */
    private function dispatch(Route $route, array $params): void
    {
        $controllerClass = $route->getController();
        $method          = $route->getMethod();

        if (!class_exists($controllerClass::class)) {
            throw new Exception('Controller not found');
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $method)) {
            throw new Exception(
                "Method '$method' not found in controller"
            );
        }

        // Call the controller method with parameters
        call_user_func_array([$controller, $method], $params);
    }
}
