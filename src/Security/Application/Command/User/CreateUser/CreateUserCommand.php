<?php
namespace App\Security\Application\Command\User\CreateUser;

use Symfony\Component\Validator\Constraints as Assert;
readonly final class CreateUserCommand
{
    public function __construct(
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
