<?php
namespace App\Ingredient\Presentation\Http\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

readonly final class IngredientListJsonResponse
{
    private function __construct()
    {}

    public static function create(array $items): JsonResponse
    {
        return new JsonResponse([
            'items' => $items
        ]);
    }
}
