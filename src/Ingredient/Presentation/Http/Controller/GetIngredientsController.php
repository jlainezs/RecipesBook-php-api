<?php
namespace App\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Query\Ingredient\GetIngredients\GetIngredientsQueryDto;
use App\Ingredient\Application\Query\Ingredient\GetIngredients\GetIngredientsQueryFactory;
use App\Ingredient\Presentation\Http\Resource\IngredientResource;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\JsonApi\JsonApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
final class GetIngredientsController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GetIngredientsQueryFactory $queryFactory,
        private readonly IngredientResource $resource
    ){}

    #[Route('/ingredients', name: 'ingredient_list', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['GET'])]
    public function __invoke(
        #[MapQueryString(validationFailedStatusCode: Response::HTTP_BAD_REQUEST)]
        GetIngredientsQueryDto $queryDto
    ): JsonApiResponse
    {
        $query = $this->queryFactory->createFromDto($queryDto);
        $response = $this->queryBus->ask($query);

        return new JsonApiResponse(
            $this->resource->collection($response->items, $query->offset, $query->limit)
        );
    }
}
