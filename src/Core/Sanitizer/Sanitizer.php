<?php

namespace Core\Sanitizer;

/**
 * Class Sanitizer */
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
        $sanitized = filter_var($email, FILTER_SANITIZE_EMAIL);

        if ($sanitized === false) {
            throw new \InvalidArgumentException('Invalid email address');
        }

        return $sanitized;
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
        $sanitized =  filter_var($url, FILTER_SANITIZE_URL);

        if ($sanitized === false) {
            throw new \InvalidArgumentException('Invalid URL');
        }

        return $sanitized;
    }

    /**
     * Sanitize an integer by removing all illegal characters.
     *
     * @param mixed $value The value to sanitize.
     *
     * @return integer The sanitized integer or null if not valid.
     */
    public static function sanitizeInteger(mixed $value): int
    {
        $sanitized = filter_var($value, FILTER_SANITIZE_NUMBER_INT);

        if ($sanitized === false && !is_numeric($value)) {
            throw new \InvalidArgumentException('Invalid integer');
        }

        return (int) $sanitized;
    }

    /**
     * Sanitize a float by removing all illegal characters.
     *
     * @param mixed $value The value to sanitize.
     *
     * @return float The sanitized float or null if not valid.
     */
    public static function sanitizeFloat(mixed $value): float
    {
        $sanitized = filter_var(
            $value,
            FILTER_SANITIZE_NUMBER_FLOAT,
            FILTER_FLAG_ALLOW_FRACTION
        );

        if ($sanitized === false && !is_numeric($value)) {
            throw new \InvalidArgumentException('Invalid integer');
        }

        return (float) $sanitized;
    }

    /**
     * Sanitize a JSON string by decoding it to an associative array.
     *
     * @param string $json The JSON string to sanitize.
     *
     * @return array<mixed> The sanitized array or null if the JSON is invalid.
     */
    public static function sanitizeJson(string $json): array
    {
        $decoded = json_decode($json, true);

        if ($decoded === null) {
            throw new \InvalidArgumentException('Invalid JSON');
        }

        return (array) $decoded;
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
