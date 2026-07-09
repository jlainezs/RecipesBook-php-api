<?php
namespace App\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Query\Recipe\RecipesQuery;
use App\Recipe\Presentation\Http\Response\RecipesListJsonResponse;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class GetRecipesListController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus
    ){}

    #[Route('/api/v1/recipes', name: 'get_recipes_list', methods: ['GET'])]
    public function __invoke(Request $request): JsonResponse
    {
        $query = new RecipesQuery(
            offset: $request->query->getInt('offset'),
            limit: $request->query->getInt('limit', 20)
        );
        $response = $this->queryBus->ask($query);

        return RecipesListJsonResponse::create($response->items);
    }
}
