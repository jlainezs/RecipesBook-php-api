<?php
namespace App\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\ShoppingList\Application\Command\ShoppingListCreate\ShoppingListCreateCommand;
use App\ShoppingList\Application\Command\ShoppingListCreate\ShoppingListCreateDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class CreateShoppingListController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/shopping-lists/create', name: 'shopping_lists_create', methods: ['POST'])]
    public function  __invoke(
        #[MapRequestPayload]
        ShoppingListCreateDto $request
    ): JsonResponse
    {
        $cmd = new ShoppingListCreateCommand($request->name);
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 201);
    }
}
