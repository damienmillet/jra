<?php

namespace Controllers;

use Core\Auth\Role;
use Core\Clock\Clock;
use Core\Request;
use Core\Response;
use Core\Route;
use Services\ConvertService;
use Services\ExportService;

/**
 * Class ExportController
 * Controller for handling user-related actions.
 */
class ExportController
{
    /**
     * Handles the GET request.
     *
     * @param Request  $request  The request object.
     * @param Response $response The response object.
     *
     * @return Response
     */
    #[Route(path: '/api/export/contacts/{:slug}', method: 'GET', secure: Role::ADMIN)]
    public function get(Request $request, Response $response): Response
    {

        $slug = $request->getParam('slug');

        if (!$slug || !in_array($slug, ['kms', 'years', 'none'])) {
            return $response->sendJson(
                ['error' => 'slug is required or not valid'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $data = match ($slug) {
            'kms' => ExportService::getKms(30000, 'gte'),
            'years' => ExportService::getYears(3, 'gte'),
            'none' => ExportService::getNone()
        };

        // Génération du contenu CSV
        $csvContent = ConvertService::toCsv($data);

        $clock = new Clock();
        $date  = $clock->now()->format('Y-m-d_H-i-s');

        $response->setHeader('Content-Type', 'text/csv');
        $response->setHeader(
            'Content-Disposition',
            "attachment; filename='export_$date.csv'"
        );
        return $response->send($csvContent);
    }
}
