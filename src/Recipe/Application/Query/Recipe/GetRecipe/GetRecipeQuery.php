<?php
namespace App\Recipe\Application\Query\Recipe\GetRecipe;

use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;

#[IsAuthorized(SecurityRole::USER)]
final readonly class GetRecipeQuery
{
    public function __construct(
        public string $id
    ) {}
}
