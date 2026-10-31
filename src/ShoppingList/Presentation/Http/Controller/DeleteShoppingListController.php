<?php
namespace App\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use App\ShoppingList\Application\Command\ShoppingListDelete\DeleteShoppingListCommandFactory;
use App\ShoppingList\Application\Command\ShoppingListDelete\DeleteShoppingListRouteDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class DeleteShoppingListController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly DeleteShoppingListCommandFactory $commandFactory
    ){}

    #[Route('/api/v1/shopping-lists/{id}', name: 'shopping-lists_delete_instance', methods: ['DELETE'])]
    public function __invoke(#[MapRouteParameter] DeleteShoppingListRouteDto $request): JsonResponse
    {
        $cmd = $this->commandFactory->createFromDto($request);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 204);
    }
}
