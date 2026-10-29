<?php
namespace App\Season\Presentation\Http\Controller;

use App\Season\Application\Command\Season\DeleteSeason\DeleteSeasonCommandFactory;
use App\Season\Application\Command\Season\DeleteSeason\DeleteSeasonDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class DeleteSeasonController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly DeleteSeasonCommandFactory $cmdFactory,
    ){}

    #[Route('/api/v1/seasons/{id}', name: 'seasons_delete_instance', methods: ['DELETE'])]
    public function __invoke(#[MapRouteParameter] DeleteSeasonDto $dto): JsonResponse
    {
        $cmd = $this->cmdFactory->createFromDto($dto);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 204);
    }
}
