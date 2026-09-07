<?php
namespace App\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Query\Recipe\GetRecipes\GetRecipesDto;
use App\Recipe\Application\Query\Recipe\GetRecipes\GetRecipesQuery;
use App\Recipe\Presentation\Http\Response\RecipesListJsonResponse;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

final class GetRecipesListController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/recipes', name: 'get_recipes_list', methods: ['GET'])]
    public function __invoke(#[MapQueryString] GetRecipesDto $dto): JsonResponse
    {
        $query = new GetRecipesQuery(
            offset: $dto->offset,
            limit: $dto->limit
        );
        $this->validator->validate($query);
        $response = $this->queryBus->ask($query);

        return RecipesListJsonResponse::create($response->items);
    }
}
