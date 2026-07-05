<?php
namespace App\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\UnitOfMeasureCreateCommand;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class PostUnitOfMeasureController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/units-of-measure/create', name: 'unit_of_measure_create', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $name = $request->getPayload()->getString('name');
        $symbol = $request->getPayload()->getString('symbol');
        $uom = UnitOfMeasureEnum::from($request->getPayload()->getInt('uomType'));
        $cmd = new UnitOfMeasureCreateCommand($name, $symbol, $uom);
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 201);
    }
}
