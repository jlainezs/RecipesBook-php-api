<?php
namespace App\Security\Application\Command\User\CreateUser;

use App\Security\Application\Service\ApplicationPasswordHasher;
use App\Security\Domain\Exceptions\UserEmailAlreadyInUseException;
use App\Security\Domain\Model\User;
use App\Security\Domain\Model\UserRole;
use App\Security\Domain\Repository\UserRepositoryInterface;
use App\Security\Domain\Service\UserEmailUniquenessChecker;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\Email;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateUserCommandHandler
{
    public function __construct(
        private UserRepositoryInterface   $repository,
        private ApplicationPasswordHasher $applicationPasswordHasher,
        private UserEmailUniquenessChecker $emailUniquenessChecker,
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     * @throws UserEmailAlreadyInUseException
     */
    public function __invoke(CreateUserCommand $command): void
    {
        $this->emailUniquenessChecker->isEmailUnique(new Email($command->email));
        $roles = empty($command->roles)
            ? [UserRole::USER]
            : $command->roles;

        $user = User::create(
            id: $command->id,
            email: $command->email,
            password: '',
            firstName: $command->firstName,
            lastName: $command->lastName,
            roles: [],
        );
        $user->setRoles($roles);

        $hashedPassword = $this->applicationPasswordHasher->hash($user, $command->password);
        $user->changePassword($hashedPassword);
        $this->repository->save($user);
    }
}
