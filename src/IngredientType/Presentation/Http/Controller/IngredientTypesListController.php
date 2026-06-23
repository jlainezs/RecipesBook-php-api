<?php

namespace App\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Query\IngredientType\IngredientTypesQuery;
use App\IngredientType\Presentation\Http\Response\IngredientTypesListJsonResponse;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
final class IngredientTypesListController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    #[Route('/ingredient-types', name: 'ingredient_types_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $response = $this->queryBus->ask(new IngredientTypesQuery());
        return IngredientTypesListJsonResponse::create($response->items);
    }
}
