<?php
namespace App\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Query\Ingredient\IngredientsQuery;
use App\Ingredient\Presentation\Http\Response\IngredientListJsonResponse;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class GetIngredientsListController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {}

    #[Route('/api/v1/ingredients', name: 'ingredient_list', methods: ['GET'])]
    public function __invoke(Request $request): JsonResponse
    {
        $response = $this->queryBus->ask(
            new IngredientsQuery(
                offset: $request->query->getInt('offset'),
                limit: $request->query->getInt('limit', 10),
            )
        );

        return IngredientListJsonResponse::create($response->items);
    }
}
