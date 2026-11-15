<?php
namespace App\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\ValueResolver\MapJsonApiPayload;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use App\ShoppingList\Application\Command\ShoppingListUpdate\UpdateShoppingListCommandFactory;
use App\ShoppingList\Application\Command\ShoppingListUpdate\UpdateShoppingListDto;
use App\ShoppingList\Application\Command\ShoppingListUpdate\UpdateShoppingListRouteDto;
use App\ShoppingList\Presentation\Http\Resource\ShoppingListResource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UpdateShoppingListController extends AbstractController
{
    function __construct(
        private readonly CommandBus $commandBus,
        private readonly UpdateShoppingListCommandFactory $commandFactory
    ){}

    #[Route('/api/v1/shopping-lists/{id}', name: 'put_shopping-list', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['PATCH'])]
    public function __invoke(
        #[MapRouteParameter]
        UpdateShoppingListRouteDto $requestId,
        #[MapJsonApiPayload(ShoppingListResource::TYPE)]
        UpdateShoppingListDto $request
    ): Response
    {
        $cmd = $this->commandFactory->createFromRequest($requestId, $request);
        $this->commandBus->dispatch($cmd);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
