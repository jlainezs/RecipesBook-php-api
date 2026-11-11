<?php
namespace App\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Query\Recipe\GetRecipes\GetRecipesDto;
use App\Recipe\Application\Query\Recipe\GetRecipes\GetRecipesQueryFactory;
use App\Recipe\Presentation\Http\Resource\RecipeResource;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\JsonApi\JsonApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
final class GetRecipesController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GetRecipesQueryFactory $queryFactory,
        private readonly RecipeResource $resource
    ){}

    #[Route('/recipes', name: 'get_recipes_list', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['GET'])]
    public function __invoke(
        #[MapQueryString(validationFailedStatusCode: Response::HTTP_BAD_REQUEST)]
        GetRecipesDto $dto
    ): JsonApiResponse
    {
        $query = $this->queryFactory->createFromDto($dto);
        $response = $this->queryBus->ask($query);

        return new JsonApiResponse($this->resource->collection($response->items, $query->offset, $query->limit));
    }
}
