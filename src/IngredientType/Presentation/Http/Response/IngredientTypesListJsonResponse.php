<?php

namespace App\IngredientType\Presentation\Http\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

readonly final class IngredientTypesListJsonResponse
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
