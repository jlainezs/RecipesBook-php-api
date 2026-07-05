<?php
namespace App\Season\Presentation\Http\Controller;

use App\Season\Application\Command\Season\SeasonCreateCommand;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class PostSeasonController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/seasons/create', name: 'seasons_create', methods: ['POST'])]
    public function __invoke(Request $request):JsonResponse
    {
        $name = $request->getPayload()->getString('name');
        $cmd = new SeasonCreateCommand($name);
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 201);
    }
}
