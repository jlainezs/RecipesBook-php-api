<?php
namespace App\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\JsonApi\JsonApiResponse;
use App\Shared\Presentation\Http\ValueResolver\MapJsonApiPayload;
use App\ShoppingList\Application\Command\ShoppingListCreate\CreateShoppingListCommandFactory;
use App\ShoppingList\Application\Command\ShoppingListCreate\CreateShoppingListDto;
use App\ShoppingList\Presentation\Http\Resource\ShoppingListResource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreateShoppingListController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly CreateShoppingListCommandFactory $commandFactory,
        private readonly ShoppingListResource $resource
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Route('/api/v1/shopping-lists', name: 'shopping_lists_create', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['POST'])]
    public function  __invoke(
        #[MapJsonApiPayload(ShoppingListResource::TYPE)]
        CreateShoppingListDto $request
    ): JsonApiResponse
    {
        $cmd = $this->commandFactory->createFromDto($request);
        $this->commandBus->dispatch($cmd);

        return new JsonApiResponse(
            document: $this->resource->itemReference($cmd->id),
            status:Response::HTTP_CREATED,
            headers: [
                'Location' => $this->resource->selfUrl($cmd->id),
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
            ]
        );
    }
}
