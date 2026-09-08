<?php
namespace App\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\ShoppingList\Application\Query\SoppingLists\ShoppingListsDto;
use App\ShoppingList\Application\Query\SoppingLists\ShoppingListsQuery;
use App\ShoppingList\Presentation\Http\Response\ShoppingListsJsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

final class GetShoppingListsController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/shopping-lists', name: 'get_shopping-lists_list', methods: ['GET'])]
    public function __invoke(#[MapQueryString] ShoppingListsDto $dto): JsonResponse
    {
        $query = new ShoppingListsQuery(
            offset: $dto->offset,
            limit: $dto->limit
        );
        $this->validator->validate($query);
        $response = $this->queryBus->ask($query);

        return ShoppingListsJsonResponse::create($response->items);
    }
}
