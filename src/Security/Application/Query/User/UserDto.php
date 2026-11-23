<?php
namespace App\Security\Application\Query\User;

use App\Security\Domain\Model\User;
use DateTimeImmutable;

final readonly class UserDto
{
    public function __construct(
        public string $id,
        public string $email,
        public string $firstName,
        public string $lastName,
        public array $roles,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt
    ) {}

    public static function fromDomain(User $user): self
    {
        return new self(
            $user->getId()->toString(),
            $user->getEmail()->value(),
            $user->getFirstName()->value(),
            $user->getLastName()->value(),
            $user->getRoles(),
            $user->getCreatedAt(),
            $user->getUpdatedAt()
        );
    }
}
