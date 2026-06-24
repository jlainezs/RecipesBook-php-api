<?php

namespace App\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Command\IngredientType\IngredientTypeCreateCommand;
use App\IngredientType\Application\Command\IngredientType\IngredientTypeDeleteCommand;
use App\IngredientType\Application\Query\IngredientType\IngredientTypeInstanceQuery;
use App\IngredientType\Domain\Exceptions\IngredientTypeEmptyNameException;
use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\Response\JsonErrorResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/ingredient-types')]
final class IngredientTypeController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus, private readonly CommandBus $commandBus)
    {}

    #[Route('/{id}', name: 'ingredient_type_get_instance', methods: ['GET'])]
    public function getInstance(Request $request):JsonResponse
    {
        $id = $request->attributes->getString('id');

        try
        {
            $response = $this->queryBus->ask(new IngredientTypeInstanceQuery($id));
            return new JsonResponse($response);
        }
        catch (HandlerFailedException $t)
        {
            if ($t->getPrevious() instanceof IngredientTypeNotFoundException)
            {
                return new JsonErrorResponse(
                    $t->getPrevious()->getMessage(),
                    404
                );
            }

            return new JsonErrorResponse($t->getMessage(), 500);
        }
    }

    #[Route('/create', name: 'ingredient_type_create', methods: ['POST'])]
    public function create(Request $request):JsonResponse
    {
        $name = $request->getPayload()->getString('name');

        try
        {
            $this->commandBus->dispatch(new IngredientTypeCreateCommand($name));

            return new JsonResponse(['message' => 'Ingredient type created successfully'], 201);
        }
        catch (HandlerFailedException $t)
        {
            if ($t->getPrevious() instanceof IngredientTypeEmptyNameException) {
                return new JsonErrorResponse($t->getPrevious()->getMessage(), 400);
            }

            return new JsonErrorResponse($t->getMessage(), 500);
        }
    }

    #[Route('/{id}', name: 'ingredient_type_delete', methods: ['DELETE'])]
    public function delete(Request $request): JsonResponse
    {
        $id = $request->attributes->getString('id');

        try {
            $this->commandBus->dispatch(new IngredientTypeDeleteCommand($id));

            return new JsonResponse(null, 204);
        } catch (HandlerFailedException $t) {
            if ($t->getPrevious() instanceof IngredientTypeNotFoundException) {
                return new JsonErrorResponse($t->getPrevious()->getMessage(), 404);
            }

            return new JsonErrorResponse($t->getMessage(), 500);
        }
    }
}
