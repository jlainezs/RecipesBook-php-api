<?php
namespace App\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\JsonApi\JsonApiResponse;
use App\Shared\Presentation\Http\ValueResolver\MapJsonApiPayload;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\UpdateUnitOfMeasure\UpdateUnitOfMeasureCommandFactory;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\UpdateUnitOfMeasure\UpdateUnitOfMeasureDto;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\UpdateUnitOfMeasure\UpdateUnitOfMeasureRouteDto;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasure\GetUnitOfMeasureQuery;
use App\UnitOfMeasure\Presentation\Http\Resource\UnitOfMeasureResource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

final class UpdateUnitOfMeasureController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly QueryBus $queryBus,
        private readonly UpdateUnitOfMeasureCommandFactory $commandFactory,
        private readonly UnitOfMeasureResource $resource,
    ){}

    /**
     * Returns 200 with the resource instead of 204: updatedAt is changed by the server.
     */
    #[Route('/api/v1/units-of-measure/{id}', name: 'unit_of_measure_update', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['PATCH'])]
    public function __invoke(
        #[MapRouteParameter]
        UpdateUnitOfMeasureRouteDto $routeDto,
        #[MapJsonApiPayload(UnitOfMeasureResource::TYPE)]
        UpdateUnitOfMeasureDto      $request
    ): JsonApiResponse
    {
        $cmd = $this->commandFactory->createFromRequest($routeDto, $request);
        $this->commandBus->dispatch($cmd);
        $response = $this->queryBus->ask(new GetUnitOfMeasureQuery($cmd->id));

        return new JsonApiResponse($this->resource->item($response->unitOfMeasure));
    }

}
