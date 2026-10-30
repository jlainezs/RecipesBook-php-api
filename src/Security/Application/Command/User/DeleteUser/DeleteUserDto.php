<?php
namespace App\Security\Application\Command\User\DeleteUser;

use Symfony\Component\Validator\Constraints as Assert;
final readonly class DeleteUserDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $id
    ){}
}
