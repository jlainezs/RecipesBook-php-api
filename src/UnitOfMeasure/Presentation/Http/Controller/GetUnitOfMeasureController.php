<?php
namespace App\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\JsonApi\JsonApiResponse;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasure\GetUnitOfMeasureQueryFactory;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasure\GetUnitOfMeasureRouteQueryDto;
use App\UnitOfMeasure\Presentation\Http\Resource\UnitOfMeasureResource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

final class GetUnitOfMeasureController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GetUnitOfMeasureQueryFactory $queryFactory,
        private readonly UnitOfMeasureResource $resource,
    ){}

    #[Route('/api/v1/units-of-measure/{id}', name: 'unit_of_measure_get_instance', methods: ['GET'], defaults: [JsonApi::ROUTE_FLAG => true])]
    public function __invoke(#[MapRouteParameter] GetUnitOfMeasureRouteQueryDto $routeDto): JsonApiResponse
    {
        $query = $this->queryFactory->createFromDto($routeDto);
        $response = $this->queryBus->ask($query);

        return new JsonApiResponse($this->resource->item($response->unitOfMeasure));
    }
}
