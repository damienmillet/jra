<?php

namespace Core;

/**
 * Class Config
 */
class Config
{
    /**
     * The singleton instance of the Config class.
     *
     * @var self|null
     */
    private static ?self $instance = null;

    /**
     * @var array<mixed> $config Configuration settings.
     */
    private array $config = [];


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
     * @return array<string,string> An array containing a message indicating var_dump
     *               is not allowed.
     */
    public function __debugInfo()
    {
        return ['message' => 'var_dump is not allowed on this object'];
    }


    /**
     * Prevents cloning of the instance.
     *
     * @return void
     */
    private function __clone()
    {
        throw new \Exception('Cloning is forbidden.');
    }


    /**
     * Prevents unserializing of the instance.
     *
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
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
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
                $this->loadEnv($filePath);
                break;

            case 'json':
                $this->loadJson($filePath);
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
    public function get(string $key, mixed $default = null)
    {
        return ($this->config[$key] ?? $default);
    }


    /**
     * Loads environment variables from a .env file.
     *
     * @param string $filePath The path to the .env file.
     *
     * @return void
     */
    private function loadEnv(string $filePath): void
    {
        $lines = file($filePath, (FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));

        if ($lines === false) {
            throw new \RuntimeException(
                "Error reading configuration file: $filePath"
            );
        }

        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
                // Ignore les commentaires
            }

            [
             $key,
             $value,
            ] = explode('=', $line, 2);

            $key   = trim($key);
            $value = $this->parseValue(trim($value));

            $this->config[$key] = $value;
        }
    }


    /**
     * Loads configuration from a JSON file.
     *
     * @param string $filePath The path to the JSON file.
     *
     * @return void
     */
    private function loadJson(string $filePath): void
    {
        $content = file_get_contents($filePath);

        if ($content === false) {
            throw new \RuntimeException(
                "Error reading configuration file: $filePath"
            );
        }

        $data = (array) json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException(
                'Error parsing JSON file: ' . json_last_error_msg()
            );
        }

        $this->config = array_merge($this->config, $data);
    }


    /**
     * Parses a configuration value.
     *
     * @param string $value The value to parse.
     *
     * @return mixed The parsed value.
     */
    private function parseValue(string $value)
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
