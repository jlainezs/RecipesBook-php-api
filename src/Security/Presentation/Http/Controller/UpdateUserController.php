<?php
namespace App\Security\Presentation\Http\Controller;

use App\Security\Application\Command\User\UpdateUser\UpdateUserCommandFactory;
use App\Security\Application\Command\User\UpdateUser\UpdateUserDto;
use App\Security\Application\Command\User\UpdateUser\UpdateUserRouteDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class UpdateUserController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly UpdateUserCommandFactory $commandFactory
    ){}

    #[Route('/api/v1/users/{id}', name: 'users_update', methods: ['PUT'])]
    public function __invoke(#[MapRouteParameter] UpdateUserRouteDto $userId, #[MapRequestPayload] UpdateUserDto $dto): JsonResponse
    {
        $cmd = $this->commandFactory->createFromRequestDto($userId, $dto);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
