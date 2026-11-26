<?php
namespace App\Recipe\Application\Command\RecipeDelete;

use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;

#[IsAuthorized(SecurityRole::USER)]
final readonly class DeleteRecipeCommand
{
    public function __construct(
        public string $id
    ){}
}
