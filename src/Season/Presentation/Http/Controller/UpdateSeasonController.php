<?php
namespace App\Season\Presentation\Http\Controller;

use App\Season\Application\Command\Season\UpdateSeason\UpdateSeasonCommand;
use App\Season\Application\Command\Season\UpdateSeason\UpdateSeasonDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class UpdateSeasonController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/seasons/{id}', name: 'seasons_update_instance', methods: ['PUT'])]
    public function __invoke(string $id, #[MapRequestPayload] UpdateSeasonDto $dto): JsonResponse
    {
        $cmd = new UpdateSeasonCommand($id, $dto->name);
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 204);
    }
}
