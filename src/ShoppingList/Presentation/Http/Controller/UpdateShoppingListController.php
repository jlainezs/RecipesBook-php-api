<?php
namespace App\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\ShoppingList\Application\Command\ShoppingListUpdate\ShoppingListUpdateCommand;
use App\ShoppingList\Application\Command\ShoppingListUpdate\ShoppingListUpdateDto;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class UpdateShoppingListController extends AbstractController
{
    function __construct(
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator,
        private readonly LoggerInterface $logger
    ){}

    #[Route('/api/v1/shopping-lists/{id}', name: 'put_shopping-list', methods: ['PUT'])]
    public function __invoke(
        string $id,
        #[MapRequestPayload]
        ShoppingListUpdateDto $request
    ): JsonResponse
    {
        $name = $request->name;
        $cmd = new ShoppingListUpdateCommand($id, $name);
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 204);
    }
}
