<?php
namespace App\Season\Presentation\Http\Controller;

use App\Season\Application\Command\Season\CreateSeason\CreateSeasonCommand;
use App\Season\Application\Command\Season\CreateSeason\CreateSeasonCommandFactory;
use App\Season\Application\Command\Season\CreateSeason\CreateSeasonDto;
use App\Season\Application\Command\Season\CreateSeason\CreateSeasonResponse;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class CreateSeasonController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly CreateSeasonCommandFactory $commandFactory,
        private readonly UrlGeneratorInterface $urlGenerator,
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Route('/api/v1/seasons', name: 'seasons_create', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] CreateSeasonDto $dto):JsonResponse
    {
        $cmd = $this->commandFactory->createFromDto($dto);
        $this->commandBus->dispatch($cmd);
        $response = new CreateSeasonResponse($cmd->id);

        return new JsonResponse(
            $response,
            Response::HTTP_CREATED,
            [
                'Content-Type' => 'application/json',
                'Location' => $this->urlGenerator->generate('seasons_get_instance', ['id' => $cmd->id], UrlGeneratorInterface::ABSOLUTE_URL),
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
            ]
        );
    }
}
