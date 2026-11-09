<?php
namespace App\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Query\IngredientType\GetIngredientTypes\GetIngredientTypesDto;
use App\IngredientType\Application\Query\IngredientType\GetIngredientTypes\GetIngredientTypesQueryFactory;
use App\IngredientType\Presentation\Http\Resource\IngredientTypeResource;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\JsonApi\JsonApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
final class GetIngredientTypesController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GetIngredientTypesQueryFactory $queryFactory,
        private readonly IngredientTypeResource $resource
    ){}

    #[Route('/ingredient-types', name: 'ingredient_types_list', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['GET'])]
    public function __invoke(
        #[MapQueryString(validationFailedStatusCode: Response::HTTP_BAD_REQUEST)]
        GetIngredientTypesDto $queryDto
    ): JsonApiResponse
    {
        $query = $this->queryFactory->createFromDto($queryDto);
        $response = $this->queryBus->ask($query);

        return new JsonApiResponse(
            $this->resource->collection($response->items, $query->offset, $query->limit)
        );
    }
}
