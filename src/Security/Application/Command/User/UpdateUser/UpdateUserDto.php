<?php
namespace App\Security\Application\Command\User\UpdateUser;

final readonly class UpdateUserDto
{
    public function __construct(
        public string $email,
        public ?string $password,
        public string $firstName,
        public string $lastName,
        public array $roles
    ){}
}
