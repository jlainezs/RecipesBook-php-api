<?php
namespace App\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\DeleteUnitOfMeasure\DeleteUnitOfMeasureCommandFactory;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\DeleteUnitOfMeasure\DeleteUnitOfMeasureRouteDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DeleteUnitOfMeasureController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly DeleteUnitOfMeasureCommandFactory $commandFactory
    ){}

    #[Route('/api/v1/units-of-measure/{id}', name: 'unit_of_measure_delete', methods: ['DELETE'], defaults: [JsonApi::ROUTE_FLAG => true])]
    function __invoke(#[MapRouteParameter] DeleteUnitOfMeasureRouteDto $request): Response
    {
        $cmd = $this->commandFactory->createFromRequest($request);
        $this->commandBus->dispatch($cmd);

        return new Response(status: Response::HTTP_NO_CONTENT);
    }


}
