<?php
namespace App\ShoppingList\Presentation\Http\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class ShoppingListsCollectionJsonResponse
{
    public static function create(array $items): JsonResponse
    {
        return new JsonResponse([
            'items' => $items
        ]);
    }
}
