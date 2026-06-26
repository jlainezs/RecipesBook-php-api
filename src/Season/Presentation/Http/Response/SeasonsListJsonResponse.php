<?php

namespace App\Season\Presentation\Http\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

readonly final class SeasonsListJsonResponse
{
    protected function __construct()
    {}

    public static function create(array $items): JsonResponse
    {
        return new JsonResponse([
            'items' => $items,
        ]);
    }
}
