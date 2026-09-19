<?php
namespace App\Security\Application\Query\User;

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
}
