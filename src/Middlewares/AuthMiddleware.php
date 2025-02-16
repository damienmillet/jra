<?php

namespace Middlewares;

use Core\Auth;
use Core\Auth\Role;
use Core\Request;
use Core\Response;
use Core\Http\Server\MiddlewareInterface;

/**
 * Class AuthMiddleware
 * Middleware for handling user authentication.
 */
class AuthMiddleware
{
    /**
     * Handle the incoming request.
     *
     * @param Request  $request  The incoming request.
     * @param Response $response The response object.
     * @param Role     $security The security level of the route.
     *
     * @return boolean|Response
     */
    public static function handle(
        Request $request,
        Response $response,
        Role $security
    ): bool|Response {
        $authorization = $request->getHeader('HTTP_AUTHORIZATION');

        // si l'entête Authorization est manquante
        if ($authorization === null) {
            return $response->sendJson(
                ['error' => 'Authorization header is missing'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        if (!is_string($authorization)) {
            return $response->sendJson(
                ['error' => 'Invalid authorization header'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        // on divise l'entête du token
        list($type, $token) = explode(' ', $authorization);

        // mauvais type de token, on sort !
        if ($type !== 'Bearer') {
            return $response->sendJson(
                ['error' => 'Invalid authorization type'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        // on verifie sa validité
        $secret = Auth::verifyAndDecodeToken($token);

        // on sort si le token est invalide
        if (isset($secret['error'])) {
            return $response->sendJson(
                ['error' => $secret['error']],
                Response::HTTP_UNAUTHORIZED
            );
        }

        if (!isset($secret['payload'])) {
            return $response->sendJson(
                ['error' => 'Invalid token'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        // on verifie si le role de l'utilisateur est autorisé à accéder à la route
        $allowed = isset($secret['payload']) && $secret['payload']['roles']
        && in_array($security->name, $secret['payload']['roles']);



        // si le type de token n'est pas Bearer ou si le token n'est pas valide
        if (!$allowed) {
            return $response->sendJson(
                ['error' => 'Unauthorized'],
                Response::HTTP_FORBIDDEN
            );
        }

        // on store l'utilisateur dans la request
        $request->setAuthData($secret);

        return true;
    }


    // /**
    // * Process an incoming server request.
    // *
    // * @param ServerRequestInterface $request  The request object.
    // * @param RequestHandlerInterface $handler The request handler.
    // *
    // * @return ResponseInterface
    // */
    // public function process(
    // ServerRequestInterface $request,
    // RequestHandlerInterface $handler,
    // Response $response
    // ) {
    // $response = $handler->handle($request, $response);
    // $response->send();
    // return $response;
    // }
}
