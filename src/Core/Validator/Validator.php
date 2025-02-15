<?php

 namespace Core\Validator;

 /**
  * Class Validator
  */
class Validator
{
    /**
     * Check if the value is a string.
     *
     * @param mixed $value The value to check.
     *
     * @return boolean True if the value is a string, false otherwise.
     */
    public static function isString(mixed $value): bool
    {
        return is_string($value);
    }

    /**
     * Check if the value is an integer.
     *
     * @param mixed $value The value to check.
     *
     * @return boolean True if the value is an integer, false otherwise.
     */
    public static function isInteger(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }


    /**
     * Check if the integer value is within a specified range.
     *
     * @param mixed   $value The value to check.
     * @param integer $min   The minimum range value.
     * @param integer $max   The maximum range value.
     *
     * @return boolean True if the value is within the range, false otherwise.
     */
    public static function isIntegerInRange(mixed $value, int $min, int $max): bool
    {
        return filter_var(
            $value,
            FILTER_VALIDATE_INT,
            [
             'options' => [
                           'min_range' => $min,
                           'max_range' => $max,
                          ],
            ]
        ) !== false;
    }

    /**
     * Check if the value is a valid ID.
     *
     * @param mixed $value The value to check.
     *
     * @return boolean True if the value is a valid ID, false otherwise.
     */
    public static function isId(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_INT)  !== false && $value > 0;
    }


    /**
     * Check if the value is a valid email address.
     *
     * @param string $email The email address to check.
     *
     * @return boolean True if the value is a valid email address, false otherwise.
     */
    public static function isEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL)  !== false;
    }


    /**
     * Check if the value is a valid URL.
     *
     * @param string $url The URL to check.
     *
     * @return boolean True if the value is a valid URL, false otherwise.
     */
    public static function isUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Check if the value matches a given pattern.
     *
     * @param string $value   The value to check.
     * @param string $pattern The pattern to match against.
     *
     * @return boolean True if the value matches the pattern, false otherwise.
     */
    public static function matchesPattern(string $value, string $pattern): bool
    {
        return preg_match($pattern, $value)  !== false;
    }

    /**
     * Check if the value is a valid JSON string.
     *
     * @param string $json The JSON string to check.
     *
     * @return boolean True if the value is a valid JSON string, false otherwise.
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
     * @return boolean True if the value is a boolean, false otherwise.
     */
    public static function isBoolean(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)  !== false;
    }

    /**
     * Check if the value is null.
     *
     * @param mixed $value The value to check.
     *
     * @return boolean True if the value is null, false otherwise.
     */
    public static function isNull(mixed $value): bool
    {
        return is_null($value);
    }

    /**
     * Checks if the given value is empty.
     *
     * @param string $value The value to check.
     * @return boolean True if the value is empty, false otherwise.
     */
    public static function isEmptyString(string $value): bool
    {
        return $value === '';
    }

    /**
     * Checks if the given array is not empty.
     *
     * @param array<mixed> $value The array to check.
     * @return boolean Returns true if the array is not empty, false otherwise.
     */
    public static function isEmptyArray(array $value): bool
    {
        return $value === [];
    }

    /**
     * Checks if the given value is an array.
     *
     * @param mixed $value The value to check.
     * @return boolean True if the value is an array, false otherwise.
     */
    public static function isArray(mixed $value): bool
    {
        return is_array($value);
    }

    /**
     * Check if all keys exist in the given JSON array.
     *
     * @param array<string> $keys The keys to check.
     * @param array<mixed>  $json The JSON array to check against.
     *
     * @return boolean True if all keys exist, false otherwise.
     */
    public static function hasKeys(array $keys, array $json): bool
    {
        if (self::isEmptyArray($json)) {
            return false;
        }

        $json = (object) $json;

        foreach ($keys as $key) {
            if (!property_exists($json, $key)) {
                return false;
            }
        }
        return true;
    }
}
