<?php
namespace App\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasure\GetUnitOfMeasureQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class GetUnitOfMeasureController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/units-of-measure/{id}', name: 'unit_of_measure_get_instance', methods: ['GET'])]
    public function __invoke(string $id): JsonResponse
    {
        $query = new GetUnitOfMeasureQuery($id);
        $this->validator->validate($query);
        $response = $this->queryBus->ask($query);

        return new JsonResponse($response);
    }
}
