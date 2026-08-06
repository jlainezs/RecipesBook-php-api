<?php
namespace App\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\ShoppingList\Application\Query\SoppingListsCollection\ShoppingListsCollectionQuery;
use App\ShoppingList\Presentation\Http\Response\ShoppingListsCollectionJsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ShoppingListsCollectionController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus
    ){}

    #[Route('/api/v1/shopping-lists', name: 'get_shopping-lists_list', methods: ['GET'])]
    public function __invoke(Request $request): JsonResponse
    {
        $query = new ShoppingListsCollectionQuery(
            offset: $request->query->getInt('offset'),
            limit: $request->query->getInt('limit', 20)
        );
        $response = $this->queryBus->ask($query);

        return ShoppingListsCollectionJsonResponse::create($response->items);
    }
}
