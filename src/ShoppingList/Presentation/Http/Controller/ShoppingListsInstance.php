<?php
namespace App\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\ShoppingList\Application\Query\ShoppingListInstance\ShoppingListInstanceQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ShoppingListsInstance extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/shopping-lists/{id}', name: 'get_shopping-list', methods: ['GET'])]
    public function __invoke(
        string $id
    ): JsonResponse
    {
        $query = new ShoppingListInstanceQuery($id);
        $this->validator->validate($query);
        $response = $this->queryBus->ask($query);

        return new JsonResponse($response);
    }
}
