<?php
namespace App\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\JsonApi\JsonApiResponse;
use App\Shared\Presentation\Http\ValueResolver\MapJsonApiPayload;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\CreateUnitOfMeasure\CreateUnitOfMeasureCommandFactory;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\CreateUnitOfMeasure\CreateUnitOfMeasureDto;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasure\GetUnitOfMeasureQuery;
use App\UnitOfMeasure\Presentation\Http\Resource\UnitOfMeasureResource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreateUnitOfMeasureController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly QueryBus $queryBus,
        private readonly CreateUnitOfMeasureCommandFactory $commandFactory,
        private readonly UnitOfMeasureResource $resource,
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Route('/api/v1/units-of-measure', name: 'unit_of_measure_create', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['POST'])]
    public function __invoke(
        #[MapJsonApiPayload(UnitOfMeasureResource::TYPE)]
        CreateUnitOfMeasureDto $request
    ): JsonApiResponse
    {
        $cmd = $this->commandFactory->createFromDto($request);
        $this->commandBus->dispatch($cmd);
        $response = $this->queryBus->ask(new GetUnitOfMeasureQuery($cmd->id));

        return new JsonApiResponse(
            document:$this->resource->item($response->unitOfMeasure),
            status:Response::HTTP_CREATED,
            headers:[
                'Location' => $this->resource->selfUrl($cmd->id),
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
            ]
        );
    }
}
