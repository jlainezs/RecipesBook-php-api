<?php
namespace App\ShoppingList\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\ShoppingList\Application\Command\ShoppingListCreate\CreateShoppingListCommandFactory;
use App\ShoppingList\Application\Command\ShoppingListCreate\CreateShoppingListDto;
use App\ShoppingList\Application\Command\ShoppingListCreate\CreateShoppingListResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class CreateShoppingListController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly CreateShoppingListCommandFactory $commandFactory,
        private readonly UrlGeneratorInterface $urlGenerator
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Route('/api/v1/shopping-lists', name: 'shopping_lists_create', methods: ['POST'])]
    public function  __invoke(
        #[MapRequestPayload]
        CreateShoppingListDto $request
    ): JsonResponse
    {
        $cmd = $this->commandFactory->createFromDto($request);
        $this->commandBus->dispatch($cmd);
        $response = new CreateShoppingListResponse($cmd->id);

        return new JsonResponse(
            data: $response,
            status:Response::HTTP_CREATED,
            headers: [
                'Content-Type' => 'application/json',
                'Location' => $this->urlGenerator->generate('get_shopping-list', ['id' => $cmd->id], UrlGeneratorInterface::ABSOLUTE_URL),
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
            ]
        );
    }
}
