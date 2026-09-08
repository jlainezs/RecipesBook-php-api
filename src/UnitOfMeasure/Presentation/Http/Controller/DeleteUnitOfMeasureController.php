<?php
namespace App\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\DeleteUnitOfMeasure\DeleteUnitOfMeasureCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class DeleteUnitOfMeasureController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/units-of-measure/{id}', name: 'unit_of_measure_delete', methods: ['DELETE'])]
    function __invoke(string $id): JsonResponse
    {
        $cmd = new DeleteUnitOfMeasureCommand($id);
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 204);
    }


}
