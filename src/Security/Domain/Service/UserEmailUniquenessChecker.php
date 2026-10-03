<?php
namespace App\Security\Domain\Service;
use App\Security\Domain\Exceptions\UserEmailAlreadyInUseException;
use App\Security\Infrastructure\Repository\UserRepository;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Shared\Domain\ValueObjects\Email;

final readonly class UserEmailUniquenessChecker
{
    public function __construct(
        private UserRepository $userRepository
    ){}

    /**
     * @throws UserEmailAlreadyInUseException
     */
    public function isEmailUnique(Email $email): void
    {
        if ($this->userRepository->findOneByEmail($email) !== null)
        {
           throw new UserEmailAlreadyInUseException($email);
        }
    }

    /**
     * @throws UserEmailAlreadyInUseException
     */
    public function isEmailUniqueExceptUser(Email $email, AggregateRootId $userId): void
    {
        if ($this->userRepository->findOneByEmailExcceptUser($email, $userId) !== null)
        {
           throw new UserEmailAlreadyInUseException($email);
        }
    }
}
