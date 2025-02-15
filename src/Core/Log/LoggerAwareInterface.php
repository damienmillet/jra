<?php

namespace Core\Log;

use Core\Log\LoggerInterface;

/**
 * Interface LoggerAwareInterface
 */
interface LoggerAwareInterface
{
    /**
     * Sets a logger instance on the object.
     *
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger);
}
