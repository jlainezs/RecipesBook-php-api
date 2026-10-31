<?php
namespace App\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use App\ShoppingList\Application\Query\GetShoppingList\GetShoppingListQueryFactory;
use App\ShoppingList\Application\Query\GetShoppingList\GetShoppingListQueryRouteDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class GetShoppingList extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GetShoppingListQueryFactory $queryFactory
    ){}

    #[Route('/api/v1/shopping-lists/{id}', name: 'get_shopping-list', methods: ['GET'])]
    public function __invoke(
        #[MapRouteParameter] GetShoppingListQueryRouteDto $request
    ): JsonResponse
    {
        $query = $this->queryFactory->createFromDto($request);
        $response = $this->queryBus->ask($query);

        return new JsonResponse($response);
    }
}
