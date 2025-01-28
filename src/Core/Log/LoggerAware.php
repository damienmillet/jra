<?php

namespace Core\Log;

/**
 * Class LoggerAware
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class LoggerAware implements LoggerAwareInterface
{
    protected $logger;


    /**
     * Sets a logger instance on the object.
     *
     * This method allows you to set a logger instance on the object.
     *
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
