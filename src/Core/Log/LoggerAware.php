<?php

namespace Core\Log;

/**
 * Class LoggerAware
 */
class LoggerAware implements LoggerAwareInterface
{
    /**
     * @var LoggerInterface The logger instance.
     */
    protected $logger;


    /**
     * Sets a logger instance on the object.
     *
     * This method allows you to set a logger instance on the object.
     *
     * @param LoggerInterface $logger Logger Interface.
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
