<?php
namespace App\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasures\GetUnitsOfMeasureDto;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasures\GetUnitsOfMeasureQuery;
use App\UnitOfMeasure\Presentation\Http\Response\UnitsOfMeasureListJsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
final class GetUnitsOfMeasureController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/units-of-measure', name: 'unit_of_measure_list', methods: ['GET'])]
    public function __invoke(#[MapQueryString] GetUnitsOfMeasureDto $dto): JsonResponse
    {
        $query = new GetUnitsOfMeasureQuery(
            offset: $dto->offset,
            limit: $dto->limit
        );
        $this->validator->validate($query);
        $response = $this->queryBus->ask($query);

        return UnitsOfMeasureListJsonResponse::create($response->items);
    }
}
