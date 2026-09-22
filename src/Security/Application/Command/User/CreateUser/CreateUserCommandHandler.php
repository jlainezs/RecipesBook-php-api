<?php
namespace App\Security\Application\Command\User\CreateUser;

use App\Security\Application\Service\ApplicationPasswordHasher;
use App\Security\Domain\Model\User;
use App\Security\Domain\Model\UserRole;
use App\Security\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateUserCommandHandler
{
    public function __construct(
        private UserRepositoryInterface   $repository,
        private ApplicationPasswordHasher $applicationPasswordHasher,
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(CreateUserCommand $command): void
    {
        $user = User::create(
            email: $command->email,
            password: '',
            firstName: $command->firstName,
            lastName: $command->lastName,
            roles: [UserRole::USER]
        );
        $hashedPassword = $this->applicationPasswordHasher->hash($user, $command->password);
        $user->changePassword($hashedPassword);
        $this->repository->save($user);
    }
}
