<?php
namespace App\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Bus\QueryBus;
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
    public function __construct(private readonly QueryBus $queryBus, private readonly CommandBus $commandBus)
    {}

    #[Route('/{id}', name: 'unit_of_measure_get_instance', methods: ['GET'])]
    public function getInstance(Request $request): JsonResponse
    {
        $id = $request->attributes->getString('id');

        try
        {
            $response = $this->queryBus->ask(new UnitOfMeasureInstanceQuery($id));
            return new JsonResponse($response);
        }
        catch (HandlerFailedException $t)
        {
            if ($t->getPrevious() instanceof UnitOfMeasureNotFoundException)
            {
                return new JsonErrorResponse(
                    $t->getPrevious()->getMessage(),
                    404
                );
            }

            return new JsonErrorResponse($t->getMessage(), 500);
        }
    }

    #[Route('/{id}', name: 'unit_of_measure_update', methods: ['PUT'])]
    public function updateInstance(Request $request): JsonResponse
    {
        $id = $request->attributes->getString('id');
        $name = $request->getPayload()->getString('name');
        $symbol = $request->getPayload()->getString('symbol');
        $uom = UnitOfMeasureEnum::from($request->getPayload()->getInt('uomType'));

        try {
            $this->commandBus->dispatch(new UnitOfMeasureUpdateCommand($id, $name, $symbol, $uom));
            return new JsonResponse(null, 204);
        }
        catch (HandlerFailedException $t)
        {
            if ($t->getPrevious() instanceof UnitOfMeasureNotFoundException) {
                return new JsonErrorResponse($t->getPrevious()->getMessage(), 404);
            }

            return new JsonErrorResponse($t->getMessage(), 500);
        }
    }

    #[Route('/create', name: 'unit_of_measure_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $name = $request->getPayload()->getString('name');
        $symbol = $request->getPayload()->getString('symbol');
        $uom = UnitOfMeasureEnum::from($request->getPayload()->getInt('uomType'));

        try {
            $this->commandBus->dispatch(new UnitOfMeasureCreateCommand($name, $symbol, $uom));
            return new JsonResponse(null, 201);
        }
        catch (HandlerFailedException $t)
        {
            if ($t->getPrevious() instanceof UnitOfMeasureEmptyNameException)
            {
                return new JsonErrorResponse($t->getPrevious()->getMessage(), 400);
            }

            if ($t->getPrevious() instanceof UnitOfMeasureEmptySymbolException)
            {
                return new JsonErrorResponse($t->getPrevious()->getMessage(), 400);
            }

            return new JsonErrorResponse($t->getMessage(), 500);
        }
    }

    #[Route('/{id}', name: 'unit_of_measure_delete', methods: ['DELETE'])]
    function deleteInstance(Request $request): JsonResponse
    {
        $id = $request->attributes->getString('id');

        try {
            $this->commandBus->dispatch(new UnitOfMeasureDeleteCommand($id));
            return new JsonResponse(null, 204);
        }
        catch (HandlerFailedException $t)
        {
            if ($t->getPrevious() instanceof UnitOfMeasureNotFoundException)
            {
                return new JsonErrorResponse($t->getPrevious()->getMessage(), 404);
            }

            return new JsonErrorResponse($t->getMessage(), 500);
        }
    }
}
