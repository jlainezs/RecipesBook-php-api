<?php
namespace App\Security\Application\Query\User\GetUser;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetUserQuery
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
    ) {}
}
