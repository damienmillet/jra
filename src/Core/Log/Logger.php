<?php

namespace Core\Log;

use Core\Log\LoggerInterface;
use Core\Log\LogLevel;
use Core\Clock\Clock;

/**
 * Class Logger
 */
class Logger implements LoggerInterface
{
    private string $logFile;


    /**
     * Logs an emergency message.
     *
     * @param string       $message The message to log.
     * @param array<mixed> $context The context array.
     *
     * @return void
     */
    public function emergency(string $message, array $context = []): void
    {
        $this->log($message, LogLevel::EMERGENCY);
    }


    /**
     * Logs an alert message.
     *
     * @param string               $message The message to log.
     * @param array<string,string> $context The context array.
     *
     * @return void
     */
    public function alert(string $message, array $context = []): void
    {
        $this->log($message, LogLevel::ALERT);
    }


    /**
     * Logs a critical message.
     *
     * @param string               $message The message to log.
     * @param array<string,string> $context The context array.
     *
     * @return void
     */
    public function critical(string $message, array $context = []): void
    {
        $this->log($message, LogLevel::CRITICAL);
    }


    /**
     * Logs an error message.
     *
     * @param string               $message The message to log.
     * @param array<string,string> $context The context array.
     *
     * @return void
     */
    public function error(string $message, array $context = []): void
    {
        $this->log($message, LogLevel::ERROR);
    }


    /**
     * Logs a warning message.
     *
     * @param string               $message The message to log.
     * @param array<string,string> $context The context array.
     *
     * @return void
     */
    public function warning(string $message, array $context = []): void
    {
        $this->log($message, LogLevel::WARNING);
    }


    /**
     * Logs a notice message.
     *
     * @param string               $message The message to log.
     * @param array<string,string> $context The context array.
     *
     * @return void
     */
    public function notice(string $message, array $context = []): void
    {
        $this->log($message, LogLevel::NOTICE);
    }


    /**
     * Logs an info message.
     *
     * @param string               $message The message to log.
     * @param array<string,string> $context The context array.
     *
     * @return void
     */
    public function info(string $message, array $context = []): void
    {
        $this->log($message, LogLevel::INFO);
    }


    /**
     * Logs a debug message.
     *
     * @param string               $message The message to log.
     * @param array<string,string> $context The context array.
     *
     * @return void
     */
    public function debug(string $message, array $context = []): void
    {
        $this->log($message, LogLevel::DEBUG);
    }


    /**
     * Logger constructor.
     *
     * @param string $logFile The path to the log file.
     */
    public function __construct(string $logFile)
    {
        $this->setLogFile($logFile);

        $dir = dirname($logFile);
        if (! is_dir($dir)) {
            mkdir($dir, 0750, true);
        }

        // check if the log file exists
        if (!is_file($logFile)) {
            touch($logFile);
        }

        // check if can create the log file
        if (!is_writable($dir)) {
            throw new \Exception('The log file is not writable.');
        }
    }


    /**
     * Setter of logFile
     *
     * @param string $logFile The path to the log file.
     *
     * @return Logger
     */
    public function setLogFile(string $logFile): Logger
    {
        $this->logFile = $logFile;
        return $this;
    }


    /**
     * Getter of logFile
     *
     * @return string
     */
    public function getLogFile(): string
    {
        return $this->logFile;
    }


    /**
     * Logs a message to the log file.
     *
     * @param string               $level   The log level.
     * @param string               $message The message to log.
     * @param array<string,string> $context The context array.
     *
     * @return void
     */
    public function log(string $level, string $message, array $context = []): void
    {
        // generate the log data
        $clock = new Clock();
        $date  = $clock->now()->format('Y-m-d H:i:s');
        $data  = '[' . $date . '][' . $level . '] ' . $message . "\n";
        // send to the log file
        $this->sendLogToFile($data);
        // send to the linux logger (syslog)
        // useful for monitoring
        // $this->sendToSyslog($data);
    }


    /**
     * Sends log data to the system logger.
     *
     * @param string $data The log data to send.
     *
     * @return void
     */
    public function sendToSyslog(string $data): void
    {
        $command = 'logger -t Jra -p user.info "' . $data . '"';
        exec($command);
    }


    /**
     * Sends log data to the log file.
     *
     * @param string $data The message to log.
     *
     * @return void
     */
    public function sendLogToFile(string $data): void
    {
        file_put_contents($this->getLogFile(), $data, FILE_APPEND);
    }
}
