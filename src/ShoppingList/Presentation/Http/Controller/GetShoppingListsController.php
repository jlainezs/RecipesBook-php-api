<?php
namespace App\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\JsonApi\JsonApiResponse;
use App\ShoppingList\Application\Query\GetSoppingLists\GetShoppingListsQueryDto;
use App\ShoppingList\Application\Query\GetSoppingLists\GetShoppingListsQueryFactory;
use App\ShoppingList\Presentation\Http\Resource\ShoppingListResource;
use App\ShoppingList\Presentation\Http\Response\ShoppingListsJsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

final class GetShoppingListsController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GetShoppingListsQueryFactory $queryFactory,
        private readonly ShoppingListResource $resource
    ){}

    #[Route('/api/v1/shopping-lists', name: 'get_shopping-lists_list',  defaults: [JsonApi::ROUTE_FLAG => true], methods: ['GET'])]
    public function __invoke(
        #[MapQueryString(validationFailedStatusCode: Response::HTTP_BAD_REQUEST)]
        GetShoppingListsQueryDto $dto
    ): JsonApiResponse
    {
        $query = $this->queryFactory->createFromDto($dto);
        $response = $this->queryBus->ask($query);

        return new JsonApiResponse($this->resource->collection($response->items, $query->offset, $query->limit));
    }
}
