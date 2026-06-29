<?php
namespace App\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Command\Ingredient\IngredientCreateCommand;
use App\Ingredient\Application\Command\Ingredient\IngredientDeleteCommand;
use App\Ingredient\Application\Command\Ingredient\IngredientUpdateCommand;
use App\Ingredient\Application\Query\Ingredient\IngredientInstanceQuery;
use App\Ingredient\Domain\Exceptions\IngredientNotFoundException;
use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\Response\JsonErrorResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/ingredients')]
final class IngredientController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus, private readonly CommandBus $commandBus)
    {}

    #[Route('/{id}', name: 'ingredient_get_instance', methods: ['GET'])]
    public function getInstance(Request $request): JsonResponse
    {
        $id = $request->attributes->get('id');

        try
        {
            $response = $this->queryBus->ask(new IngredientInstanceQuery($id));
            return new JsonResponse($response);
        }
        catch (HandlerFailedException $t)
        {
            if ($t->getPrevious() instanceof IngredientNotFoundException)
            {
                return new JsonErrorResponse(
                    $t->getPrevious()->getMessage(), 404
                );
            }

            return new JsonErrorResponse(
                $t->getMessage(), 500
            );
        }
    }

    #[Route('/{id}', name: 'ingredient_update_instance', methods: ['PUT'])]
    public function updateInstance(Request $request): JsonResponse
    {
        $id = $request->attributes->getString('id');
        $name = $request->getPayload()->getString('name');
        $description = $request->getPayload()->getString('description');
        $ingredientTypeId = $request->getPayload()->getString('ingredientTypeId');

        try
        {
            $cmd = new IngredientUpdateCommand(
                $id, $name, $description, $ingredientTypeId
            );
            $this->commandBus->dispatch($cmd);
            return new JsonResponse(null, 204);
        }
        catch (HandlerFailedException $t)
        {
            if (
                ($t->getPrevious() instanceof IngredientNotFoundException)
                ||
                ($t->getPrevious() instanceof IngredientTypeNotFoundException)
            )
            {
                return new JsonErrorResponse(
                    $t->getPrevious()->getMessage(), 404
                );
            }

            return new JsonErrorResponse(
                $t->getMessage(), 500
            );
        }
    }

    #[Route('/create', name: 'ingredient_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $name = $request->getPayload()->getString('name');
        $description = $request->getPayload()->getString('description');
        $ingredientTypeId = $request->getPayload()->getString('ingredientTypeId');

        try {
            $this->commandBus->dispatch(new IngredientCreateCommand($name, $description, $ingredientTypeId));
            return new JsonResponse(null, 201);
        }
        catch (HandlerFailedException $t)
        {
            if ($t->getPrevious() instanceof IngredientTypeNotFoundException)
            {
                return new JsonErrorResponse(
                    $t->getPrevious()->getMessage(), 404
                );
            }

            return new JsonErrorResponse(
                $t->getMessage(), 500
            );
        }
    }

    #[Route('/{id}', name: 'ingredient_delete', methods: ['DELETE'])]
    public function delete(Request $request): JsonResponse
    {
        $id = $request->attributes->getString('id');

        try{
            $this->commandBus->dispatch(new IngredientDeleteCommand($id));
            return new JsonResponse(null, 204);
        }
        catch (HandlerFailedException $t)
        {
            if ($t->getPrevious() instanceof IngredientNotFoundException)
            {
                return new JsonErrorResponse(
                    $t->getPrevious()->getMessage(), 404
                );
            }

            return new JsonErrorResponse(
                $t->getMessage(), 500
            );
        }
    }
}
