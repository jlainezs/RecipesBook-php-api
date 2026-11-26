<?php
namespace App\Security\Application\Command\User\CreateUser;

use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;
use Symfony\Component\Validator\Constraints as Assert;

#[IsAuthorized(SecurityRole::USER)]
readonly final class CreateUserCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
        #[Assert\NotBlank(message: "Email is required")]
        #[Assert\Email(message: "Invalid email provided")]
        public string $email,
        #[Assert\NotBlank(message: "Password is required")]
        public string $password,
        #[Assert\NotBlank(message: "First name is required")]
        public string $firstName,
        #[Assert\NotBlank(message: "Last name is required")]
        public string $lastName,
        public array $roles
    ){}
}
