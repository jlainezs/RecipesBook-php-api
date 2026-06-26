<?php

namespace App\Season\Presentation\Http\Controller;

use App\Season\Application\Command\Season\SeasonCreateCommand;
use App\Season\Application\Command\Season\SeasonDeleteCommand;
use App\Season\Application\Command\Season\SeasonUpdateCommand;
use App\Season\Application\Query\Season\SeasonInstanceQuery;
use App\Season\Domain\Exceptions\SeasonEmptyNameException;
use App\Season\Domain\Exceptions\SeasonNotFoundException;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\Response\JsonErrorResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/seasons')]
final class SeasonController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus, private readonly CommandBus $commandBus)
    {}

    #[Route('/{id}', name: 'seasons_get_instance', methods: ['GET'])]
    public function getInstance(Request $request):JsonResponse
    {
        $id = $request->attributes->getString('id');

        try
        {
            $response = $this->queryBus->ask(new SeasonInstanceQuery($id));
            return new JsonResponse($response);
        }
        catch (HandlerFailedException $t)
        {
            if ($t->getPrevious() instanceof SeasonNotFoundException)
            {
                return new JsonErrorResponse(
                    $t->getPrevious()->getMessage(),
                    404
                );
            }

            return new JsonErrorResponse($t->getMessage(), 500);
        }
    }

    #[Route('/{id}', name: 'seasons_update_instance', methods: ['PUT'])]
    public function updateInstance(Request $request): JsonResponse
    {
        $name = $request->getPayload()->getString('name');
        $id = $request->attributes->getString('id');

        try
        {
            $this->commandBus->dispatch(new SeasonUpdateCommand($id, $name));
            return new JsonResponse(null, 204);
        }
        catch (HandlerFailedException $t)
        {
            if ($t->getPrevious() instanceof SeasonNotFoundException) {
                return new JsonErrorResponse($t->getPrevious()->getMessage(), 404);
            }

            return new JsonErrorResponse($t->getMessage(), 500);
        }
    }

    #[Route('/create', name: 'seasons_create', methods: ['POST'])]
    public function create(Request $request):JsonResponse
    {
        $name = $request->getPayload()->getString('name');

        try
        {
            $this->commandBus->dispatch(new SeasonCreateCommand($name));

            return new JsonResponse(['message' => 'Ingredient type created successfully'], 201);
        }
        catch (HandlerFailedException $t)
        {
            if ($t->getPrevious() instanceof SeasonEmptyNameException) {
                return new JsonErrorResponse($t->getPrevious()->getMessage(), 400);
            }

            return new JsonErrorResponse($t->getMessage(), 500);
        }
    }

    #[Route('/{id}', name: 'seasons_delete_instance', methods: ['DELETE'])]
    public function deleteInstance(Request $request): JsonResponse
    {
        $id = $request->attributes->getString('id');

        try {
            $this->commandBus->dispatch(new SeasonDeleteCommand($id));

            return new JsonResponse(null, 204);
        } catch (HandlerFailedException $t) {
            if ($t->getPrevious() instanceof SeasonNotFoundException) {
                return new JsonErrorResponse($t->getPrevious()->getMessage(), 404);
            }

            return new JsonErrorResponse($t->getMessage(), 500);
        }
    }
}
