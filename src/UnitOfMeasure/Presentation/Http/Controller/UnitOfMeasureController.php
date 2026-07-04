<?php
namespace App\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Presentation\Http\Response\JsonErrorResponse;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\UnitOfMeasureCreateCommand;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\UnitOfMeasureDeleteCommand;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\UnitOfMeasureUpdateCommand;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\UnitOfMeasureInstanceQuery;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureEmptyNameException;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureEmptySymbolException;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureNotFoundException;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/units-of-measure')]
final class UnitOfMeasureController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/{id}', name: 'unit_of_measure_get_instance', methods: ['GET'])]
    public function getInstance(Request $request): JsonResponse
    {
        $id = $request->attributes->getString('id');
        $query = new UnitOfMeasureInstanceQuery($id);
        $this->validator->validate($query);
        $response = $this->queryBus->ask($query);

        return new JsonResponse($response);
    }

    #[Route('/{id}', name: 'unit_of_measure_update', methods: ['PUT'])]
    public function updateInstance(Request $request): JsonResponse
    {
        $id = $request->attributes->getString('id');
        $name = $request->getPayload()->getString('name');
        $symbol = $request->getPayload()->getString('symbol');
        $uom = UnitOfMeasureEnum::from($request->getPayload()->getInt('uomType'));
        $cmd = new UnitOfMeasureUpdateCommand($id, $name, $symbol, $uom);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 204);
    }

    #[Route('/create', name: 'unit_of_measure_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $name = $request->getPayload()->getString('name');
        $symbol = $request->getPayload()->getString('symbol');
        $uom = UnitOfMeasureEnum::from($request->getPayload()->getInt('uomType'));
        $cmd = new UnitOfMeasureCreateCommand($name, $symbol, $uom);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 201);
    }

    #[Route('/{id}', name: 'unit_of_measure_delete', methods: ['DELETE'])]
    function deleteInstance(Request $request): JsonResponse
    {
        $id = $request->attributes->getString('id');
        $cmd = new UnitOfMeasureDeleteCommand($id);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 204);
    }
}
