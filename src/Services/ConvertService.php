<?php

/**
 * Service file for defining the ConvertService class.
 * php version 8.2
 *
 * @category Services
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */

namespace Services;

/**
 * Class ConvertService
 *
 * @category Services
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class ConvertService
{
    /**
     * Converts an array of objects to an array of arrays.
     *
     * @param array $array An array of objects.
     *
     * @return array An array of arrays.
     */
    public static function toObjectArray($array)
    {
        return array_map(fn ($a) => $a->toArray(), $array);
    }


    /**
     * Convert an array to JSON.
     *
     * @param array $array The array to convert.
     *
     * @return boolean|string The JSON encoded string or false on failure.
     */
    public static function arrayToJson(array $array): bool|string
    {
        return json_encode($array, JSON_THROW_ON_ERROR);
    }


    /**
     * Generates a CSV string from an array of data.
     *
     * @param array $data The data to convert to CSV.
     *
     * @return string The CSV content.
     */
    public static function toCsv(array $data): string
    {
        // Utilisation d'un flux temporaire en mémoire
        $output = fopen('php://temp', 'r+');

        // Ajout des en-têtes CSV
        if (!empty($data)) {
            fputcsv($output, array_keys($data[0]));
        }

        // Ajout des lignes de données
        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        // Récupération du contenu du flux
        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return $csvContent;
    }

    /**
     * Converts a CSV string to an array.
     *
     * @param string $csv The CSV string to convert.
     *
     * @return array The converted array.
     */
    public static function fromCsv(string $csv): array
    {
        $lines = explode("\n", $csv);
        $data = [];
        $keys = str_getcsv(array_shift($lines));
        foreach ($lines as $line) {
            $data[] = array_combine($keys, str_getcsv($line));
        }
        return $data;
    }
}
