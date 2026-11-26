<?php
namespace App\Ingredient\Application\Command\Ingredient\DeleteIngredient;

use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;

#[IsAuthorized(SecurityRole::USER)]
readonly final class DeleteIngredientCommand
{
    public function __construct(
        public string $id
    ){}
}
