<?php
namespace App\Security\Presentation\Http\Controller;

use App\Security\Application\Command\User\CreateUser\CreateUserCommandFactory;
use App\Security\Application\Command\User\CreateUser\CreateUserDto;
use App\Security\Application\Command\User\CreateUser\CreateUserResponse;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class CreateUserController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly CreateUserCommandFactory $commandFactory,
        private readonly UrlGeneratorInterface $urlGenerator
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Route('/api/v1/users', name: 'users_create', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] CreateUserDto $createUserDto): JsonResponse
    {
        $cmd = $this->commandFactory->createFromDto($createUserDto);
        $this->commandBus->dispatch($cmd);
        $response = new CreateUserResponse($cmd->id);

        return new JsonResponse(
            data: $response,
            status:Response::HTTP_CREATED,
            headers: [
                'Content-Type' => 'application/json',
                'Location' => $this->urlGenerator->generate('users_get_instance', ['id' => $cmd->id], UrlGeneratorInterface::ABSOLUTE_URL),
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
            ]
        );
    }
}
