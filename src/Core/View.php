<?php

namespace Core;

/**
 * Class View
 */
class View
{
    /**
     * Render a view file
     *
     * @param string       $view The view file to render.
     * @param array<mixed> $data The data to pass to the view.
     *
     * @return string The rendered view content.
     */
    public static function render(string $view, array $data = [])
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
        $view = ob_get_clean();

        if ($view === false) {
            throw new \Exception("Error rendering view '{$view}'.");
        }

        return $view;
    }
}
