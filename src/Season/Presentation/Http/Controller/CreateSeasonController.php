<?php
namespace App\Season\Presentation\Http\Controller;

use App\Season\Application\Command\Season\CreateSeason\CreateSeasonCommand;
use App\Season\Application\Command\Season\CreateSeason\CreateSeasonCommandFactory;
use App\Season\Application\Command\Season\CreateSeason\CreateSeasonDto;
use App\Season\Application\Command\Season\CreateSeason\CreateSeasonResponse;
use App\Season\Presentation\Http\Resource\SeasonResource;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\JsonApi\JsonApiResponse;
use App\Shared\Presentation\Http\ValueResolver\MapJsonApiPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class CreateSeasonController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly CreateSeasonCommandFactory $commandFactory,
        private readonly SeasonResource $resource,
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Route('/api/v1/seasons', name: 'seasons_create', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['POST'])]
    public function __invoke(
        #[MapJsonApiPayload(SeasonResource::TYPE)]
        CreateSeasonDto $dto
    ):JsonApiResponse
    {
        $cmd = $this->commandFactory->createFromDto($dto);
        $this->commandBus->dispatch($cmd);

        return new JsonApiResponse(
            document: $this->resource->itemReference($cmd->id),
            status: Response::HTTP_CREATED,
            headers: [
                'Location' => $this->resource->selfUrl($cmd->id),
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
            ],
        );
    }
}
