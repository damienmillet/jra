<?php

namespace Core\Http\Message;

/**
 * Enum StatusCode
 */
enum StatusCode: int
{
    case HTTP_OK                    = 200;
    case HTTP_CREATED               = 201;
    case HTTP_NO_CONTENT            = 204;
    case HTTP_MOVED_PERMANENTLY     = 301;
    case HTTP_FOUND                 = 302;
    case HTTP_NOT_MODIFIED          = 304;
    case HTTP_BAD_REQUEST           = 400;
    case HTTP_UNAUTHORIZED          = 401;
    case HTTP_FORBIDDEN             = 403;
    case HTTP_NOT_FOUND             = 404;
    case HTTP_METHOD_NOT_ALLOWED    = 405;
    case LENGTH_REQUIRED            = 411;
    case PRECONDITION_FAILED        = 412;
    case PAYLOAD_TOO_LARGE          = 413;
    case URL_TOO_LONG               = 414;
    case UNSUPPORTED_MEDIA_TYPE     = 415;
    case RANGE_NOT_SATISFIABLE      = 416;
    case IM_A_TEAPOT                = 418;
    case TOO_MANY_REQUESTS          = 429;
    case HTTP_INTERNAL_SERVER_ERROR = 500;
}
