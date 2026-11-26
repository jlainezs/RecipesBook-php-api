<?php
namespace App\Security\Application\Query\User\GetUser;

use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;

#[IsAuthorized(SecurityRole::USER)]
final readonly class GetUserQuery
{
    public function __construct(
        public string $id,
    ) {}
}
