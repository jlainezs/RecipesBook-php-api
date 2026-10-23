<?php
namespace App\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\CreateUnitOfMeasure\CreateUnitOfMeasureCommandFactory;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\CreateUnitOfMeasure\CreateUnitOfMeasureDto;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\CreateUnitOfMeasure\CreateUnitOfMeasureResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class CreateUnitOfMeasureController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly CreateUnitOfMeasureCommandFactory $commandFactory,
        private readonly UrlGeneratorInterface $urlGenerator,
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Route('/api/v1/units-of-measure', name: 'unit_of_measure_create', methods: ['POST'])]
    public function __invoke(
        #[MapRequestPayload]
        CreateUnitOfMeasureDto $request
    ): JsonResponse
    {
        $cmd = $this->commandFactory->createFromDto($request);
        $this->commandBus->dispatch($cmd);
        $response = new CreateUnitOfMeasureResponse($cmd->id);

        return new JsonResponse(
            data:$response,
            status: Response::HTTP_CREATED,
            headers: [
                'Content-Type' => 'application/json',
                'Location' => $this->urlGenerator->generate('unit_of_measure_get_instance', ['id' => $response->id], UrlGeneratorInterface::ABSOLUTE_URL),
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
            ]
        );
    }
}
