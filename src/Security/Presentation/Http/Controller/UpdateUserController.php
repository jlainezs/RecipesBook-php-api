<?php
namespace App\Security\Presentation\Http\Controller;

use App\Security\Application\Command\User\UpdateUser\UpdateUserCommand;
use App\Security\Application\Command\User\UpdateUser\UpdateUserDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class UpdateUserController extends AbstractController
{
    public function __construct(
        private CommandBus $commandBus,
        private ApplicationDataValidator $validator
    ){}

    public function __invoke(string $id, UpdateUserDto $dto): JsonResponse
    {
        $cmd = new UpdateUserCommand(
            $id,
            $dto->email,
            $dto->password,
            $dto->firstName,
            $dto->lastName,
            $dto->roles,
        );
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
