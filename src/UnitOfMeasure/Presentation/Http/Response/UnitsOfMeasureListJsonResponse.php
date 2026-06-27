<?php
namespace App\UnitOfMeasure\Presentation\Http\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

readonly final class UnitsOfMeasureListJsonResponse
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
