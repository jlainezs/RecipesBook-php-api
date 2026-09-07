<?php
namespace App\Season\Presentation\Http\Controller;

use App\Season\Application\Command\Season\CreateSeason\CreateSeasonCommand;
use App\Season\Application\Command\Season\CreateSeason\CreateSeasonDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class CreateSeasonController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/seasons/create', name: 'seasons_create', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] CreateSeasonDto $dto):JsonResponse
    {
        $cmd = new CreateSeasonCommand($dto->name);
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 201);
    }
}
