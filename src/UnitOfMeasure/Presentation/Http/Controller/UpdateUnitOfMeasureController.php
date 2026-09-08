<?php
namespace App\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\UpdateUnitOfMeasure\UpdateUnitOfMeasureCommand;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\UpdateUnitOfMeasure\UpdateUnitOfMeasureDto;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class UpdateUnitOfMeasureController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/units-of-measure/{id}', name: 'unit_of_measure_update', methods: ['PUT'])]
    public function __invoke(string $id, UpdateUnitOfMeasureDto $request): JsonResponse
    {
        $cmd = new UpdateUnitOfMeasureCommand(
            $id, $request->name, $request->symbol, UnitOfMeasureEnum::from($request->unitOfMeasureType)
        );
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 204);
    }

}
