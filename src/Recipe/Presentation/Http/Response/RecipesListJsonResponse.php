<?php
namespace App\Recipe\Presentation\Http\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

readonly final class RecipesListJsonResponse
{
    public static function create(array $items): JsonResponse
    {
        return new JsonResponse([
            'items' => $items
        ]);
    }
}
