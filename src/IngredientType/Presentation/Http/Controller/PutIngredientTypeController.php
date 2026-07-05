<?php
namespace App\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Command\IngredientType\IngredientTypeUpdateCommand;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class PutIngredientTypeController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/ingredient-types/{id}', name: 'ingredient_types_update_instance', methods: ['PUT'])]
    public function __invoke(Request $request): JsonResponse
    {
        $name = $request->getPayload()->getString('name');
        $id = $request->attributes->getString('id');
        $cmd = new IngredientTypeUpdateCommand($id, $name);
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 204);
    }
}
