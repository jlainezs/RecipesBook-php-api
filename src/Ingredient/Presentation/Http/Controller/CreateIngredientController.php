<?php
namespace App\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Command\Ingredient\CreateIngredient\CreateIngredientCommand;
use App\Ingredient\Application\Command\Ingredient\CreateIngredient\CreateIngredientDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class CreateIngredientController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/ingredients/create', name: 'ingredient_create', methods: ['POST'])]
    public function __invoke(
        #[MapRequestPayload]
        CreateIngredientDto $request
    ): JsonResponse
    {
        $cmd = new CreateIngredientCommand(
            $request->name,
            $request->description,
            $request->ingredientTypeId
        );
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 201);
    }
}
