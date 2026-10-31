<?php
namespace App\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasure\GetUnitOfMeasureQueryFactory;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasure\GetUnitOfMeasureRouteQueryDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class GetUnitOfMeasureController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GetUnitOfMeasureQueryFactory $queryFactory
    ){}

    #[Route('/api/v1/units-of-measure/{id}', name: 'unit_of_measure_get_instance', methods: ['GET'])]
    public function __invoke(#[MapRouteParameter] GetUnitOfMeasureRouteQueryDto $routeDto): JsonResponse
    {
        $query = $this->queryFactory->createFromDto($routeDto);
        $response = $this->queryBus->ask($query);

        return new JsonResponse($response);
    }
}
