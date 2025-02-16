<?php

/**
 * Autoload function for loading classes.
 * php version 8.2
 *
 * @category Api
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     https://damien-millet.dev
 */

spl_autoload_register(
    function ($className) {
        // Remplacez les backslashes (\) par des slashes (/)
        $classFileName = str_replace('\\', '/', $className);
        $file          = dirname(__DIR__) . '/src/' . $classFileName . '.php';
        // Vérifiez si le fichier existe, sinon lancez une exception
        if (file_exists($file)) {
            include $file;
        } else {
            throw new \Exception("Cannot load class $className from file: $file");
        }
    }
);
