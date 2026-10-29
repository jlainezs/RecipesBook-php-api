<?php
namespace App\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Command\RecipeUpdate\UpdateRecipeCommandFactory;
use App\Recipe\Application\Command\RecipeUpdate\UpdateRecipeRouteDto;
use App\Recipe\Application\Command\RecipeUpdate\UpdateRecipeDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class UpdateRecipeController extends AbstractController
{
    function __construct(
        private readonly CommandBus $commandBus,
        private readonly UpdateRecipeCommandFactory $commandFactory,
        private readonly LoggerInterface $logger
    ){}

    #[Route('/api/v1/recipes/{id}', name: 'update_recipe', methods: ['PUT'])]
    public function __invoke(
        #[MapRouteParameter] UpdateRecipeRouteDto $routeId,
        #[MapRequestPayload]
        UpdateRecipeDto $request
    ): JsonResponse
    {
        $cmd = $this->commandFactory->createFromRequestDto($routeId, $request);
        $this->commandBus->dispatch($cmd);

        $this->logger->info("Recipe {$routeId->id} updated");

        return new JsonResponse(null, 204);
    }
}
