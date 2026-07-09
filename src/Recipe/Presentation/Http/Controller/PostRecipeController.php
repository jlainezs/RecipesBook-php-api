<?php
namespace App\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Command\Recipe\RecipeCreateCommand;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class PostRecipeController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/recipes/create', name: 'post_recipe', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $name = $request->getPayload()->getString('name');
        $description = $request->getPayload()->getString('description');
        $source = $request->getPayload()->getString('source');
        $servings = $request->getPayload()->getInt('servings');
        $rating = $request->getPayload()->getInt('rating');

        $command = new RecipeCreateCommand(
            name: $name,
            servings: $servings,
            rating: $rating,
            description: $description,
            source: $source
        );
        $this->validator->validate($command);
        $this->commandBus->dispatch($command);

        return new JsonResponse(null, 201);
    }
}
