<?php
namespace App\Security\Application\Command\User\UpdateUser;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateUserRouteDto
{
    public function __construct(
        #[Assert\NotBlank()]
        #[Assert\Uuid]
        public string $id,
    ){}
}
