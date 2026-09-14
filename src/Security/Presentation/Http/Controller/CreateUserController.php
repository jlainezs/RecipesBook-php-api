<?php
namespace App\Security\Presentation\Http\Controller;

use App\Security\Application\Command\User\CreateUser\CreateUserCommand;
use App\Security\Application\Command\User\CreateUser\CreateUserDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class CreateUserController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/users/create', name: 'users_create', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] CreateUserDto $createUserDto): JsonResponse
    {
        $cmd = new CreateUserCommand(
            $createUserDto->email,
            $createUserDto->password,
            $createUserDto->firstName,
            $createUserDto->lastName
        );
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 201);
    }
}
