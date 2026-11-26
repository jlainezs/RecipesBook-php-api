<?php
namespace App\Security\Application\Command\User\UpdateUser;

use App\Security\Domain\Model\UserRole;
use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;
use Symfony\Component\Validator\Constraints as Assert;

#[IsAuthorized(SecurityRole::USER)]
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

        #[Assert\All([new Assert\Type(UserRole::class)])]
        public array $roles
    ){}
}
