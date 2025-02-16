<?php

namespace Core\Log;

/**
 * Interface LoggerInterface
 */
interface LoggerInterface
{
    /**
     * System is unusable.
     *
     * @param string               $message The message to log.
     * @param array<string,string> $context The context array.
     *
     * @return void
     */
    public function emergency(string $message, array $context = []);


    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string               $message The message to log.
     * @param array<string,string> $context The context array.
     *
     * @return void
     */
    public function alert(string $message, array $context = []);


    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string               $message The message to log.
     * @param array<string,string> $context The context array.
     *
     * @return void
     */
    public function critical(string $message, array $context = []);


    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string               $message The message to log.
     * @param array<string,string> $context The context array.
     *
     * @return void
     */
    public function error(string $message, array $context = []);


    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string               $message The message to log.
     * @param array<string,string> $context The context array.
     *
     * @return void
     */
    public function warning(string $message, array $context = []);


    /**
     * Normal but significant events.
     *
     * @param string               $message The message to log.
     * @param array<string,string> $context The context array.
     *
     * @return void
     */
    public function notice(string $message, array $context = []);


    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string               $message The message to log.
     * @param array<string,string> $context The context array.
     *
     * @return void
     */
    public function info(string $message, array $context = []);


    /**
     * Detailed debug information.
     *
     * @param string               $message The message to log.
     * @param array<string,string> $context The context array.
     *
     * @return void
     */
    public function debug(string $message, array $context = []);

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed                $level   The log level.
     * @param string               $message The message to log.
     * @param array<string,string> $context The context array.
     *
     * @return void
     */
    public function log(string $level, $message, array $context = []);
}
