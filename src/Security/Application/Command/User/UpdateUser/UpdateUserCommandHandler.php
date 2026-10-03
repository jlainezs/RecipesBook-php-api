<?php
namespace App\Security\Application\Command\User\UpdateUser;

use App\Security\Application\Service\ApplicationPasswordHasher;
use App\Security\Domain\Exceptions\UserEmailAlreadyInUseException;
use App\Security\Domain\Exceptions\UserNotFoundException;
use App\Security\Domain\Repository\UserRepositoryInterface;
use App\Security\Domain\Service\UserEmailUniquenessChecker;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\RequiredName;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class UpdateUserCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private ApplicationPasswordHasher $applicationPasswordHasher,
        private UserEmailUniquenessChecker $emailUniquenessChecker,
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     * @throws UserNotFoundException
     * @throws UserEmailAlreadyInUseException
     */
    public function __invoke(UpdateUserCommand $command): void
    {
        $userId = new AggregateRootId($command->id);
        $newEmail = new Email($command->email);
        $this->emailUniquenessChecker->isEmailUniqueExceptUser($newEmail, $userId);
        $user = $this->repository->findOne($userId);

        if ($user)
        {
            $user->changeEmail($newEmail);
            $user->changeFirstName(new RequiredName($command->firstName));
            $user->changeLastName(new RequiredName($command->lastName));
            $user->setRoles($command->roles);

            if (!empty($command->password))
            {
                $hashedPassword = $this->applicationPasswordHasher->hash($user, $command->password);
                $user->changePassword($hashedPassword);
            }

            $this->repository->save($user);
        }
        else
        {
            throw new UserNotFoundException($command->id);
        }
    }
}
