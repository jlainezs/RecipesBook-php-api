<?php
namespace App\Security\Application\Command\User\UpdateUser;

use Symfony\Component\Validator\Constraints as Assert;
final readonly class UpdateUserCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,

        #[Assert\Email]
        public string $email,
        public ?string $password,

        #[Assert\NotBlank]
        public string $firstName,
        #[Assert\NotBlank]
        public string $lastName,
        public array $roles
    ){}
}
