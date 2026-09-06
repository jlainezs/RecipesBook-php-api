<?php
namespace App\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Query\Ingredient\GetIngredients\GetIngredientsQuery;
use App\Ingredient\Application\Query\Ingredient\GetIngredients\GetIngredientsQueryDto;
use App\Ingredient\Presentation\Http\Response\IngredientListJsonResponse;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

final class GetIngredientsController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/ingredients', name: 'ingredient_list', methods: ['GET'])]
    public function __invoke(
        #[MapQueryString] GetIngredientsQueryDto $queryDto
    ): JsonResponse
    {
        $query = new GetIngredientsQuery(
            offset: $queryDto->offset,
            limit: $queryDto->limit,
        );
        $this->validator->validate($query);
        $response = $this->queryBus->ask($query);

        return IngredientListJsonResponse::create($response->items);
    }
}
