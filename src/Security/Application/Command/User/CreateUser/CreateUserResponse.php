<?php
namespace App\Security\Application\Command\User\CreateUser;

class CreateUserResponse
{
    public function __construct(
        public string $userId
    ){}
}
