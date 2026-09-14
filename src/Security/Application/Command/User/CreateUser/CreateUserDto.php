<?php
namespace App\Security\Application\Command\User\CreateUser;

final readonly class CreateUserDto
{
    public function __construct(
        public string $email,
        public string $password,
        public string $firstName,
        public string $lastName,
    ){}
}
