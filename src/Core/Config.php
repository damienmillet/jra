<?php

/**
 * Core file for defining the Singleton Config class.
 * php version 8.2
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Core;

/**
 * Class Config
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

class Config
{
    private static ?self $_instance = null;

    private array $_config = [];


    /**
     * Private constructor to prevent instantiation.
     */
    private function __construct()
    {
        // useless content, only there to prevent instantiation
    }


    /**
     * Provides debug information for the object.
     *
     * @return array An array containing a message indicating var_dump
     *               is not allowed.
     */
    public function __debugInfo()
    {
        return ['message' => 'var_dump is not allowed on this object'];
    }


    /**
     * Prevents cloning of the instance.
     *
     * @throws \Exception
     * @return void
     */
    private function __clone()
    {
        throw new \Exception('Cloning is forbidden.');
    }


    /**
     * Prevents unserializing of the instance.
     *
     * @throws \Exception
     * @return void
     */
    public function __wakeup()
    {
        throw new \Exception(
            'Unserializing is forbidden' . get_class($this)
        );
    }


    /**
     * Returns the singleton instance of the Config class.
     *
     * @return self The singleton instance.
     */
    public static function getInstance(): self
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }


    /**
     * Loads configuration from a file.
     *
     * @param string $path The path to the configuration folder.
     *
     * @throws \RuntimeException If the file is not found or not readable,
     *                           or if the file format is not supported.
     * @return void
     */
    public function load(string $path): void
    {
        if (is_file($path . '/.env')) {
            $filePath = $path . '/.env';
        } elseif (is_file($path . '/.env.sample')) {
            $filePath = $path . '/.env.sample';
        } else {
            $filePath = false;
        }

        if ($filePath === false) {
            // default configuration
            return;
        }

        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new \RuntimeException(
                "Configuration file not found or unreadable: $filePath"
            );
        }

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        switch ($extension) {
        case 'env':
            $this->_loadEnv($filePath);
            break;

        case 'json':
            $this->_loadJson($filePath);
            break;

        default:
            throw new \RuntimeException(
                "Unsupported configuration file format: $extension"
            );
        }
    }


    /**
     * Retrieves a configuration value by key.
     *
     * @param string $key     The configuration key.
     * @param mixed  $default The default value to return if the key is not found.
     *
     * @return mixed The configuration value or the default value.
     */
    public function get(string $key, $default = null)
    {
        return ($this->_config[$key] ?? $default);
    }


    /**
     * Loads environment variables from a .env file.
     *
     * @param string $filePath The path to the .env file.
     *
     * @return void
     */
    private function _loadEnv(string $filePath): void
    {
        $lines = file($filePath, (FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));

        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
                // Ignore les commentaires
            }

            [
                $key,
                $value,
            ]      = explode('=', $line, 2);
            $key   = trim($key);
            $value = $this->_parseValue(trim($value));

            $this->_config[$key] = $value;
        }
    }


    /**
     * Loads configuration from a JSON file.
     *
     * @param string $filePath The path to the JSON file.
     *
     * @return void
     */
    private function _loadJson(string $filePath): void
    {
        $content = file_get_contents($filePath);
        $data    = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException(
                'Error parsing JSON file: ' . json_last_error_msg()
            );
        }

        $this->_config = array_merge($this->_config, $data);
    }


    /**
     * Parses a configuration value.
     *
     * @param string $value The value to parse.
     *
     * @return mixed The parsed value.
     */
    private function _parseValue(string $value)
    {
        if (strtolower($value) === 'true') {
            return true;
        }

        if (strtolower($value) === 'false') {
            return false;
        }

        if (is_numeric($value)) {
            return strpos($value, '.') !== false ? (float) $value : (int) $value;
        }

        return $value;
    }
}
