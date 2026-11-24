<?php
namespace App\Security\Application\Query\User\GetUser;

use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
final readonly class GetUserQuery
{
    public function __construct(
        public string $id,
    ) {}
}
