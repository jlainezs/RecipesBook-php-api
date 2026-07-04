<?php

namespace App\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Command\IngredientType\IngredientTypeCreateCommand;
use App\IngredientType\Application\Command\IngredientType\IngredientTypeDeleteCommand;
use App\IngredientType\Application\Command\IngredientType\IngredientTypeUpdateCommand;
use App\IngredientType\Application\Query\IngredientType\IngredientTypeInstanceQuery;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/ingredient-types')]
final class IngredientTypeController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/{id}', name: 'ingredient_types_get_instance', methods: ['GET'])]
    public function getInstance(Request $request):JsonResponse
    {
        $id = $request->attributes->getString('id');
        $query = new IngredientTypeInstanceQuery($id);
        $this->validator->validate($query);
        $response = $this->queryBus->ask($query);

        return new JsonResponse($response);
    }

    #[Route('/{id}', name: 'ingredient_types_update_instance', methods: ['PUT'])]
    public function updateInstance(Request $request): JsonResponse
    {
        $name = $request->getPayload()->getString('name');
        $id = $request->attributes->getString('id');
        $cmd = new IngredientTypeUpdateCommand($id, $name);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 204);
    }

    #[Route('/create', name: 'ingredient_types_create', methods: ['POST'])]
    public function create(Request $request):JsonResponse
    {
        $name = $request->getPayload()->getString('name');
        $cmd = new IngredientTypeCreateCommand($name);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(['message' => 'Ingredient type created successfully'], 201);
    }

    #[Route('/{id}', name: 'ingredient_types_delete_instance', methods: ['DELETE'])]
    public function deleteInstance(Request $request): JsonResponse
    {
        $id = $request->attributes->getString('id');
        $cmd = new IngredientTypeDeleteCommand($id);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 204);
    }
}
