<?php

namespace Core\Validator;

use Core\Sanitizer\Sanitizer;

/**
 * Class ValidatorFactory
 */
class ValidatorFactory
{
    /**
     * Validate and sanitize data based on a schema.
     *
     * @param array<mixed> $data   The data to validate and sanitize.
     * @param array<mixed> $schema The schema defining validation and sanitization rules.
     *
     * @return boolean True if all validations pass, false otherwise.
     */
    public static function validate(array &$data, array $schema): bool
    {

        if (!self::validateRequire($data, $schema)) {
            return false;
        }

        foreach ($data as $field => $value) {
            // Vérifie si le champ existe dans le schéma
            if (!array_key_exists($field, $schema)) {
                continue; // Si le champ n'est pas défini dans le schéma, on passe
            }

            $rules = $schema[$field];

            // Validation du type de données
            if (isset($rules['type']) && !self::validateType($value, $rules['type'])) {
                return false;
            }

            // Validation de l'enum
            if (!is_array($value)) {
                if (isset($rules['values']) && !self::validateEnum($value, $rules['values'])) {
                    return false;
                }

                if (isset($rules['sanitize'])) {
                    $data[$field] = self::sanitize($value, $rules['sanitize']);
                }
                continue;
            }
            var_dump($data);
        }
        return true;
    }

    /**
     * Validate required fields in the data based on the schema.
     *
     * @param array<mixed> $data   The data to validate.
     * @param array<mixed> $schema The schema defining required fields.
     *
     * @return boolean True if all required fields are present, false otherwise.
     */
    private static function validateRequire(array $data, array $schema): bool
    {
        foreach ($schema as $field => $rules) {
            // Vérifie si la propriété existe
            if (!array_key_exists($field, $data)) {
                if (!empty($rules['required'])) {
                    return false; // Champ obligatoire manquant
                }
                continue; // Champ optionnel
            }
        }
        return true;
    }

    /**
     * Validate the type of a value.
     *
     * @param mixed  $value The value to validate.
     * @param string $type  The expected type.
     *
     * @return boolean True if the value is of the expected type, false otherwise.
     */
    private static function validateType(mixed $value, string $type): bool
    {
        switch ($type) {
            case 'int':
                return filter_var($value, FILTER_VALIDATE_INT) !== false;
            case 'float':
                return filter_var($value, FILTER_VALIDATE_FLOAT) !== false;
            case 'string':
                return is_string($value);
            case 'bool':
                return is_bool(filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE));
            case 'array':
                return is_array($value);
            default:
                return false;
        }
    }

    /**
     * Validate if a value is within a set of allowed values.
     *
     * @param mixed $value  The value to validate.
     * @param array $values The array of allowed values.
     *
     * @return boolean True if the value is in the array of allowed values, false otherwise.
     */
    private static function validateEnum(mixed $value, array $values): bool
    {
        return in_array($value, $values);
    }

    /**
     * Sanitize a value based on its type.
     *
     * @param mixed  $value  The value to sanitize.
     * @param string $method The sanitization method.
     *
     * @return mixed The sanitized value.
     */
    private static function sanitize(mixed $value, string $method)
    {
        switch ($method) {
            case 'string':
                return Sanitizer::sanitizeString($value);
            case 'integer':
                return Sanitizer::sanitizeInteger($value);
            case 'email':
                return Sanitizer::sanitizeEmail($value);
            case 'url':
                return Sanitizer::sanitizeUrl($value);
            case 'array':
                return $value;
            default:
                throw new \Exception("Méthode de sanitisation inconnue : $method");
        }
    }
}
