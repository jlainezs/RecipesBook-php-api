<?php
namespace App\Security\Domain\Service;
use App\Security\Domain\Exceptions\UserEmailAlreadyInUseException;
use App\Security\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Shared\Domain\ValueObjects\Email;

class UserEmailUniquenessChecker
{
    public function __construct(
        readonly private UserRepositoryInterface $userRepository
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
        if ($this->userRepository->findOneByEmailExceptUser($email, $userId) !== null)
        {
           throw new UserEmailAlreadyInUseException($email);
        }
    }
}
