<?php
namespace App\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\ShoppingList\Application\Query\GetSoppingLists\GetShoppingListsQueryDto;
use App\ShoppingList\Application\Query\GetSoppingLists\GetShoppingListsQueryFactory;
use App\ShoppingList\Presentation\Http\Response\ShoppingListsJsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

final class GetShoppingListsController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GetShoppingListsQueryFactory $queryFactory
    ){}

    #[Route('/api/v1/shopping-lists', name: 'get_shopping-lists_list', methods: ['GET'])]
    public function __invoke(#[MapQueryString] GetShoppingListsQueryDto $dto): JsonResponse
    {
        $query = $this->queryFactory->createFromDto($dto);
        $response = $this->queryBus->ask($query);

        return ShoppingListsJsonResponse::create($response->items);
    }
}
