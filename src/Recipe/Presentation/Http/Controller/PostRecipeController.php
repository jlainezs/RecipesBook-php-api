<?php
namespace App\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Command\RecipeCreate\RecipeCreateCommand;
use App\Recipe\Application\Command\RecipeCreate\RecipeCreateDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class PostRecipeController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/recipes/create', name: 'post_recipe', methods: ['POST'])]
    public function __invoke(
        #[MapRequestPayload]
        RecipeCreateDto $request
    ): JsonResponse
    {
        $command = new RecipeCreateCommand(
            name: $request->name,
            servings: $request->servings,
            rating: $request->rating,
            description: $request->description,
            source: $request->source,
            steps: $request->steps,
            ingredients: $request->ingredients
        );
        $this->validator->validate($command);
        $this->commandBus->dispatch($command);

        return new JsonResponse(null, 201);
    }
}
