<?php

namespace Core\Http\Server;

use Core\Http\Message\ResponseInterface;
use Core\Http\Message\ServerRequestInterface;
use Core\Http\Server\RequestHandlerInterface;

interface MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface;
}
