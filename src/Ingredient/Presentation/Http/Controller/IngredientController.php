<?php
namespace App\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Command\Ingredient\IngredientCreateCommand;
use App\Ingredient\Application\Command\Ingredient\IngredientDeleteCommand;
use App\Ingredient\Application\Command\Ingredient\IngredientUpdateCommand;
use App\Ingredient\Application\Query\Ingredient\IngredientInstanceQuery;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Validation\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/ingredients')]
final class IngredientController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/{id}', name: 'ingredient_get_instance', methods: ['GET'])]
    public function getInstance(Request $request): JsonResponse
    {
        $id = $request->attributes->get('id');
        $query = new IngredientInstanceQuery($id);
        $this->validator->validate($query);
        $response = $this->queryBus->ask($query);

        return new JsonResponse($response);
    }

    #[Route('/{id}', name: 'ingredient_update_instance', methods: ['PUT'])]
    public function updateInstance(Request $request): JsonResponse
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

    #[Route('/create', name: 'ingredient_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $name = $request->getPayload()->getString('name');
        $description = $request->getPayload()->getString('description');
        $ingredientTypeId = $request->getPayload()->getString('ingredientTypeId');
        $cmd = new IngredientCreateCommand($name, $description, $ingredientTypeId);
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 201);
    }

    #[Route('/{id}', name: 'ingredient_delete', methods: ['DELETE'])]
    public function delete(Request $request): JsonResponse
    {
        $id = $request->attributes->getString('id');
        $cmd = new IngredientDeleteCommand($id);
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 204);
    }
}
