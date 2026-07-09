<?php
namespace App\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Command\Recipe\RecipeUpdateCommand;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class PutRecipeController extends AbstractController
{
    function __construct(
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/recipes/{id}', name: 'put_recipe', methods: ['PUT'])]
    public function __invoke(Request $request): JsonResponse
    {
        $id = $request->attributes->get('id');
        $name = $request->getPayload()->getString('name');
        $description = $request->getPayload()->getString('description');
        $source = $request->getPayload()->getString('source');
        $servings = $request->getPayload()->getInt('servings');
        $rating = $request->getPayload()->getInt('rating');

        $cmd = new RecipeUpdateCommand(
            id: $id,
            name: $name,
            servings: $servings,
            rating: $rating,
            description: $description,
            source: $source,
        );
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 204);
    }
}
