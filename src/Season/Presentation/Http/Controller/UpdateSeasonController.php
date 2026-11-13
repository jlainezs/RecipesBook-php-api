<?php
namespace App\Season\Presentation\Http\Controller;

use App\Season\Application\Command\Season\UpdateSeason\UpdateSeasonCommandFactory;
use App\Season\Application\Command\Season\UpdateSeason\UpdateSeasonDto;
use App\Season\Application\Command\Season\UpdateSeason\UpdateSeasonRouteDto;
use App\Season\Presentation\Http\Resource\SeasonResource;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\ValueResolver\MapJsonApiPayload;
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

    #[Route('/api/v1/seasons/{id}', name: 'seasons_update_instance', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['PATCH'])]
    public function __invoke(
        #[MapRouteParameter]
        UpdateSeasonRouteDto $seasonId,
        #[MapJsonApiPayload(SeasonResource::TYPE)]
        UpdateSeasonDto $dto
    ): Response
    {
        $cmd = $this->cmdFactory->createFromRequest($seasonId, $dto);
        $this->commandBus->dispatch($cmd);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
