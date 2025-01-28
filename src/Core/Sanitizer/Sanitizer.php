<?php

/**
 * Core file for defining the Sanitizer class.
 * php version 8.2
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Core\Sanitizer;

/**
 * Class Sanitizer
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class Sanitizer
{
 

    /**
     * Sanitize a string by converting special characters to HTML entities.
     *
     * @param string $value The string to sanitize.
     *
     * @return string The sanitized string.
     */
    public static function sanitizeString(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }



    /**
     * Sanitize an email address by removing all illegal characters.
     *
     * @param string $email The email address to sanitize.
     *
     * @return string The sanitized email address.
     */
    public static function sanitizeEmail(string $email): string
    {
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }

    /**
     * Sanitize a URL by removing all illegal characters.
     *
     * @param string $url The URL to sanitize.
     *
     * @return string The sanitized URL.
     */
    public static function sanitizeUrl(string $url): string
    {
        return filter_var($url, FILTER_SANITIZE_URL);
    }

    /**
     * Sanitize an integer by removing all illegal characters.
     *
     * @param mixed $value The value to sanitize.
     *
     * @return int|null The sanitized integer or null if not valid.
     */
    public static function sanitizeInteger($value): ?int
    {
        $sanitized = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
        return is_numeric($sanitized) ? (int) $sanitized : null;
    }

    /**
     * Sanitize a float by removing all illegal characters.
     *
     * @param mixed $value The value to sanitize.
     *
     * @return float|null The sanitized float or null if not valid.
     */
    public static function sanitizeFloat($value): ?float
    {
        $sanitized = filter_var(
            $value,
            FILTER_SANITIZE_NUMBER_FLOAT,
            FILTER_FLAG_ALLOW_FRACTION
        );
        return is_numeric($sanitized) ? (float) $sanitized : null;
    }

    /**
     * Sanitize a JSON string by decoding it to an associative array.
     *
     * @param string $json The JSON string to sanitize.
     *
     * @return array|null The sanitized array or null if the JSON is invalid.
     */
    public static function sanitizeJson(string $json): ?array
    {
        $decoded = json_decode($json, true);
        return json_last_error() === JSON_ERROR_NONE 
            ? $decoded 
            : null;
    }

    /**
     * Sanitize a file name by removing any path information and converting special 
     * characters to HTML entities.
     *
     * @param string $fileName The file name to sanitize.
     *
     * @return string The sanitized file name.
     */
    public static function sanitizeFileName(string $fileName): string
    {
        return basename(htmlspecialchars($fileName));
    }
}
