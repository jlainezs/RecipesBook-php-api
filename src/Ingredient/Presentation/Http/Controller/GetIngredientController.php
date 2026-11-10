<?php
namespace App\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Query\Ingredient\GetIngredient\GetIngredientDto;
use App\Ingredient\Application\Query\Ingredient\GetIngredient\GetIngredientQueryFactory;
use App\Ingredient\Presentation\Http\Resource\IngredientResource;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\JsonApi\JsonApiResponse;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class GetIngredientController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GetIngredientQueryFactory $queryFactory,
        private readonly IngredientResource $resource
    ){}

    #[Route('/api/v1/ingredients/{id}', name: 'ingredient_get_instance', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['GET'])]
    public function __invoke(#[MapRouteParameter] GetIngredientDto $dto): JsonResponse
    {
        $query = $this->queryFactory->createFromDto($dto);
        $response = $this->queryBus->ask($query);

        return new JsonApiResponse($this->resource->item($response->ingredientDto));
    }
}
