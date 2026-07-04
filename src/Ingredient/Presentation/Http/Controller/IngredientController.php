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
use App\Shared\Application\Validation\ApplicationDataValidator;
use App\Shared\Presentation\Http\Response\JsonErrorResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Throwable;

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

        try
        {
            $query = new IngredientInstanceQuery($id);
            $this->validator->validate($query);
            $response = $this->queryBus->ask($query);

            return new JsonResponse($response);
        }
        catch (ValidationFailedException $t)
        {
            return new JsonErrorResponse(
                $t->getMessage(), 400
            );
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
        catch (Throwable $t)
        {
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
            $this->validator->validate($cmd);

            $this->commandBus->dispatch($cmd);
            return new JsonResponse(null, 204);
        }
        catch (ValidationFailedException $t)
        {
            return new JsonErrorResponse(
                $t->getMessage(), 400
            );
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
        catch (Throwable $t)
        {
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
            $cmd = new IngredientCreateCommand($name, $description, $ingredientTypeId);
            $this->validator->validate($cmd);
            $this->commandBus->dispatch($cmd);

            return new JsonResponse(null, 201);
        }
        catch (ValidationFailedException $t)
        {
            return new JsonErrorResponse(
                $t->getMessage(), 400
            );
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
        catch (Throwable $t)
        {
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
            $cmd = new IngredientDeleteCommand($id);
            $this->validator->validate($cmd);
            $this->commandBus->dispatch($cmd);

            return new JsonResponse(null, 204);
        }
        catch (ValidationFailedException $t)
        {
            return new JsonErrorResponse(
                $t->getMessage(), 400
            );
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
        catch (Throwable $t)
        {
            return new JsonErrorResponse(
                $t->getMessage(), 500
            );
        }
    }
}
