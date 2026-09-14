<?php
namespace App\Security\Application\Command\User\CreateUser;

use App\Security\Domain\Model\User;
use App\Security\Domain\Model\UserRole;
use App\Security\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateUserCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $repository
    ){}

    public function __invoke(CreateUserCommand $command): void
    {
        $user = User::create(
            email: $command->email,
            password: $command->password,
            firstName: $command->firstName,
            lastName: $command->lastName,
            roles: [UserRole::USER]
        );
        $this->repository->save($user);
    }
}
