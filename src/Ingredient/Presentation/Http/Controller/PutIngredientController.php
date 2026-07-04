<?php
namespace App\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Command\Ingredient\IngredientUpdateCommand;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class PutIngredientController extends AbstractController
{
    function __construct(
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/ingredients/{id}', name: 'ingredient_update_instance', methods: ['PUT'])]
    public function __invoke(Request $request): JsonResponse
    {
        $id = $request->attributes->getString('id');
        $name = $request->getPayload()->getString('name');
        $description = $request->getPayload()->getString('description');
        $ingredientTypeId = $request->getPayload()->getString('ingredientTypeId');
        $cmd = new IngredientUpdateCommand(
            $id, $name, $description, $ingredientTypeId
        );
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 204);
    }
}
