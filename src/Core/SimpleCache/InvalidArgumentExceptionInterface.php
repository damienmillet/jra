<?php

namespace Core\SimpleCache;

use Core\SimpleCache\CacheExceptionInterface;

/**
 * Exception interface for invalid cache arguments.
 *
 * When an invalid argument is passed, it must throw an exception which implements
 * this interface.
 */
interface InvalidArgumentExceptionInterface extends CacheExceptionInterface
{
}
