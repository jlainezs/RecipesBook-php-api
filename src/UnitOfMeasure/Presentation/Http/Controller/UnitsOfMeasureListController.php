<?php

namespace App\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\UnitsOfMeasureQuery;
use App\UnitOfMeasure\Presentation\Http\Response\UnitsOfMeasureListJsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
final class UnitsOfMeasureListController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {}

    #[Route('/units-of-measure', name: 'unit_of_measure_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $response = $this->queryBus->ask(new UnitsOfMeasureQuery(
            offset: $request->query->getInt('offset', 0),
            limit: $request->query->getInt('limit', 10),
        ));

        return UnitsOfMeasureListJsonResponse::create($response->items);
    }
}
