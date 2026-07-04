<?php
namespace App\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Command\Ingredient\IngredientCreateCommand;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class PostIngredientController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/ingredients/create', name: 'ingredient_create', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $name = $request->getPayload()->getString('name');
        $description = $request->getPayload()->getString('description');
        $ingredientTypeId = $request->getPayload()->getString('ingredientTypeId');
        $cmd = new IngredientCreateCommand($name, $description, $ingredientTypeId);
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 201);
    }
}
