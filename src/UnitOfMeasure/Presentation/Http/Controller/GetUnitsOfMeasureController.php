<?php
namespace App\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\JsonApi\JsonApiResponse;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasures\GetUnitsOfMeasureDto;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasures\GetUnitsOfMeasureQueryFactory;
use App\UnitOfMeasure\Presentation\Http\Resource\UnitOfMeasureResource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
final class GetUnitsOfMeasureController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GetUnitsOfMeasureQueryFactory $queryFactory,
        private readonly UnitOfMeasureResource $resource,
    ){}

    #[Route('/units-of-measure', name: 'unit_of_measure_list', methods: ['GET'], defaults: [JsonApi::ROUTE_FLAG => true])]
    public function __invoke(
        #[MapQueryString(validationFailedStatusCode: Response::HTTP_BAD_REQUEST)]
        GetUnitsOfMeasureDto $dto
    ): JsonApiResponse
    {
        $query = $this->queryFactory->createFromDto($dto);
        $response = $this->queryBus->ask($query);

        return new JsonApiResponse($this->resource->collection($response->items, $query->offset, $query->limit));
    }
}
