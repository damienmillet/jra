<?php

/**
 * Endpoint for the API.
 * php version 8.2
 *
 * @category Api
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     https://damien-millet.dev
 */

require_once __DIR__ . '/autoload.php';

const API_VERSION = 4;

use Core\Request;
use Core\Response;
use Core\Router;
use Core\Log\Logger;
use Core\Config;

// Chargement des variables d'environnement
try {
    $config = Config::getInstance();
    $config->load(dirname(__DIR__));
} catch (RuntimeException $e) {
    // Log l'erreur dans le fichier php défini dans la conf ini
    // et affiche un message générique
    error_log($e->getMessage());
    echo 'Internal error. Please contact the administrator.';
    throw new \Exception('Cannot load configured file');
}

// Création des instances
$logger = new Logger(__DIR__ . '/../logs/app.log');
// Création du routeur et définition des routes
$router = new Router($logger);

// Traitement de la requête actuelle
$router->handle(new Request(), new Response());
