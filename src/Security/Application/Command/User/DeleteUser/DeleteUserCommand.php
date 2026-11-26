<?php
namespace App\Security\Application\Command\User\DeleteUser;

use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;
use Symfony\Component\Validator\Constraints as Assert;

#[IsAuthorized(SecurityRole::USER)]
final readonly class DeleteUserCommand
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $id
    ){}
}
