<?php

/**
 * Core file for defining the Validator class.
 * php version 8.2
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

 namespace Core\Validator;

 /**
  * Class Validator
  *
  * @category Core
  * @package  Jra
  * @author   Damien Millet <contact@damien-millet.dev>
  * @license  MIT License
  * @link     damien-millet.dev
  */
class Validator
{

    /**
     * Check if the value is a string.
     *
     * @param mixed $value The value to check.
     *
     * @return bool True if the value is a string, false otherwise.
     */
    public static function isString($value): bool
    {
        return is_string($value);
    }

    /**
     * Check if the value is an integer.
     *
     * @param mixed $value The value to check.
     *
     * @return bool True if the value is an integer, false otherwise.
     */
    public static function isInteger($value): bool
    {
        return !!filter_var($value, FILTER_VALIDATE_INT);
    }


    /**
     * Check if the integer value is within a specified range.
     *
     * @param mixed $value The value to check.
     * @param int   $min   The minimum range value.
     * @param int   $max   The maximum range value.
     *
     * @return bool True if the value is within the range, false otherwise.
     */
    public static function isIntegerInRange($value, int $min, int $max): bool
    {
        return !!filter_var(
            $value, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => $min, 'max_range' => $max],
            ]
        );
    }

    /**
     * Check if the value is a valid ID.
     *
     * @param mixed $value The value to check.
     *
     * @return bool True if the value is a valid ID, false otherwise.
     */
    public static function isId($value): bool
    {
        return !!filter_var($value, FILTER_VALIDATE_INT) && $value > 0;
    }


    /**
     * Check if the value is a valid email address.
     *
     * @param string $email The email address to check.
     *
     * @return bool True if the value is a valid email address, false otherwise.
     */
    public static function isEmail(string $email): bool
    {
        return !!filter_var($email, FILTER_VALIDATE_EMAIL);
    }


    /**
     * Check if the value is a valid URL.
     *
     * @param string $url The URL to check.
     *
     * @return bool True if the value is a valid URL, false otherwise.
     */
    public static function isUrl(string $url): bool
    {
        return !!filter_var($url, FILTER_VALIDATE_URL);
    }

    /**
     * Check if the value matches a given pattern.
     *
     * @param string $value   The value to check.
     * @param string $pattern The pattern to match against.
     *
     * @return bool True if the value matches the pattern, false otherwise.
     */
    public static function matchesPattern(string $value, string $pattern): bool
    {
        return !!preg_match($pattern, $value);
    }

    /**
     * Check if the value is a valid JSON string.
     *
     * @param string $json The JSON string to check.
     *
     * @return bool True if the value is a valid JSON string, false otherwise.
     */
    public static function isJson(string $json): bool
    {
        json_decode($json);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Check if the value is a boolean.
     *
     * @param mixed $value The value to check.
     *
     * @return bool True if the value is a boolean, false otherwise.
     */
    public static function isBoolean($value): bool
    {
        return !!filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }

    /**
     * Check if all keys exist in the given JSON array.
     *
     * @param array $keys The keys to check.
     * @param array $json The JSON array to check against.
     *
     * @return boolean True if all keys exist, false otherwise.
     */
    public static function hasKeys(array $keys, array  $json): bool
    {
        if (empty($json)) {
            return false;
        }

        if (is_array($json)) {
            $json = (object) $json;
        }

        foreach ($keys as $key) {
            if (!property_exists($json, $key)) {
                return false;
            }
        }
        return true;
    }
}
