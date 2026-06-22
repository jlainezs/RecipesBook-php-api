<?php

namespace App\Shared\Infrastructure\Http\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

readonly class HealthCheckJsonResponse
{
    protected function __construct()
    {
    }

    public static function create(bool $dbStatus):JsonResponse
    {
        return new JsonResponse([
            'DB' => $dbStatus
                ? 'Up'
                : 'Down',
        ]);
    }
}
