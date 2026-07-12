<?php
namespace App\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Command\RecipeUpdate\RecipeStepUpdateDto;
use App\Recipe\Application\Command\RecipeUpdate\RecipeUpdateCommand;
use App\Recipe\Application\Command\RecipeUpdate\RecipeUpdateDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class PutRecipeController extends AbstractController
{
    function __construct(
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/recipes/{id}', name: 'put_recipe', methods: ['PUT'])]
    public function __invoke(
        #[MapRequestPayload]
        RecipeUpdateDto $request
    ): JsonResponse
    {
        $id = $request->id;
        $name = $request->name;
        $description = $request->description;
        $source = $request->source;
        $servings = $request->servings;
        $rating = $request->rating;
        $steps = [];

        foreach ($request->steps as $step) {
            $steps[] = new RecipeStepUpdateDto(
                id: $step['id'] ?? null,
                description: $step['description'],
                ordering: $step['ordering']
            );
        }

        $cmd = new RecipeUpdateCommand(
            id: $id,
            name: $name,
            servings: $servings,
            rating: $rating,
            description: $description,
            source: $source,
            steps: $steps,
        );
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 204);
    }
}
