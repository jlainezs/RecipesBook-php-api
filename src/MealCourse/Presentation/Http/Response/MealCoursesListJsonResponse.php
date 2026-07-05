<?php
namespace App\MealCourse\Presentation\Http\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

readonly final class MealCoursesListJsonResponse
{
    public static function create(array $items): JsonResponse
    {
        return new JsonResponse([
            'items' => $items,
        ]);
    }
}
