<?php
namespace App\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\JsonApi\JsonApiResponse;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use App\ShoppingList\Application\Query\GetShoppingList\GetShoppingListQueryFactory;
use App\ShoppingList\Application\Query\GetShoppingList\GetShoppingListQueryRouteDto;
use App\ShoppingList\Presentation\Http\Resource\ShoppingListResource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

final class GetShoppingListController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GetShoppingListQueryFactory $queryFactory,
        private readonly ShoppingListResource $resource
    ){}

    #[Route('/api/v1/shopping-lists/{id}', name: 'get_shopping-list', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['GET'])]
    public function __invoke(
        #[MapRouteParameter] GetShoppingListQueryRouteDto $request
    ): JsonApiResponse
    {
        $query = $this->queryFactory->createFromDto($request);
        $response = $this->queryBus->ask($query);

        return new JsonApiResponse($this->resource->item($response->shoppingListDto));
    }
}
