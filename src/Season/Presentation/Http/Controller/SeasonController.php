<?php

namespace App\Season\Presentation\Http\Controller;

use App\Season\Application\Command\Season\SeasonCreateCommand;
use App\Season\Application\Command\Season\SeasonDeleteCommand;
use App\Season\Application\Command\Season\SeasonUpdateCommand;
use App\Season\Application\Query\Season\SeasonInstanceQuery;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/seasons')]
final class SeasonController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/{id}', name: 'seasons_get_instance', methods: ['GET'])]
    public function getInstance(Request $request):JsonResponse
    {
        $id = $request->attributes->getString('id');
        $query = new SeasonInstanceQuery($id);
        $this->validator->validate($query);
        $response = $this->queryBus->ask($query);

        return new JsonResponse($response);
    }

    #[Route('/{id}', name: 'seasons_update_instance', methods: ['PUT'])]
    public function updateInstance(Request $request): JsonResponse
    {
        $name = $request->getPayload()->getString('name');
        $id = $request->attributes->getString('id');
        $cmd = new SeasonUpdateCommand($id, $name);
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 204);
    }

    #[Route('/create', name: 'seasons_create', methods: ['POST'])]
    public function create(Request $request):JsonResponse
    {
        $name = $request->getPayload()->getString('name');
        $cmd = new SeasonCreateCommand($name);
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 201);
    }

    #[Route('/{id}', name: 'seasons_delete_instance', methods: ['DELETE'])]
    public function deleteInstance(Request $request): JsonResponse
    {
        $id = $request->attributes->getString('id');
        $cmd = new SeasonDeleteCommand($id);
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 204);
    }
}
