<?php
namespace App\Security\Application\Command\User\DeleteUser;

use Symfony\Component\Validator\Constraints as Assert;
final readonly class DeleteUserCommand
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $id
    ){}
}
