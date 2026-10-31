<?php
namespace App\Security\Application\Query\User\GetUser;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetUserRouteDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $id
    ){}
}
