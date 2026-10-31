<?php
namespace App\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use App\ShoppingList\Application\Command\ShoppingListUpdate\UpdateShoppingListCommandFactory;
use App\ShoppingList\Application\Command\ShoppingListUpdate\UpdateShoppingListDto;
use App\ShoppingList\Application\Command\ShoppingListUpdate\UpdateShoppingListRouteDto;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class UpdateShoppingListController extends AbstractController
{
    function __construct(
        private readonly CommandBus $commandBus,
        private readonly UpdateShoppingListCommandFactory $commandFactory,
        private readonly LoggerInterface $logger
    ){}

    #[Route('/api/v1/shopping-lists/{id}', name: 'put_shopping-list', methods: ['PUT'])]
    public function __invoke(
        #[MapRouteParameter] UpdateShoppingListRouteDto $requestId,
        #[MapRequestPayload]
        UpdateShoppingListDto $request
    ): JsonResponse
    {
        $this->logger->info('Updating shopping list', ['id' => $requestId->id]);
        $cmd = $this->commandFactory->createFromRequest($requestId, $request);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
