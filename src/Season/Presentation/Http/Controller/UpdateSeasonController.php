<?php
namespace App\Season\Presentation\Http\Controller;

use App\Season\Application\Command\Season\UpdateSeason\UpdateSeasonCommandFactory;
use App\Season\Application\Command\Season\UpdateSeason\UpdateSeasonDto;
use App\Season\Application\Command\Season\UpdateSeason\UpdateSeasonRouteDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class UpdateSeasonController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly UpdateSeasonCommandFactory $cmdFactory
    ){}

    #[Route('/api/v1/seasons/{id}', name: 'seasons_update_instance', methods: ['PUT'])]
    public function __invoke(#[MapRouteParameter] UpdateSeasonRouteDto $seasonId, #[MapRequestPayload] UpdateSeasonDto $dto): JsonResponse
    {
        $cmd = $this->cmdFactory->createFromRequest($seasonId, $dto);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
