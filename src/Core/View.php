<?php

/**
 * Core file for defining the View class.
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
 * Class View
 *
 * @category Core
 * @package  Jra
 * @author   Damien Millet <contact@damien-millet.dev>
 * @license  MIT License
 * @link     damien-millet.dev
 */
class View
{
    /**
     * Render a view file
     *
     * @param string $view The view file to render
     * @param array  $data The data to pass to the view
     *
     * @return string The rendered view content
     * @throws \Exception If the view file is not found
     */
    public static function render($view, $data = [])
    {
        // Extraire les données pour qu'elles soient accessibles dans la vue
        extract($data);
        // Commencer la mise en tampon de sortie, optimisation de la mémoire
        ob_start();

        // Vérifier si le fichier de la vue existe
        $viewFile = __DIR__ . "/../Views/{$view}.html";
        if (!file_exists($viewFile)) {
            throw new \Exception("View file '{$view}.php' not found.");
        }

        // Inclure le fichier de la vue
        include_once $viewFile;

        // Récupérer le contenu généré
        return ob_get_clean();
    }
}
