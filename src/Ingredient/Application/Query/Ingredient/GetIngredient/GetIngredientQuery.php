<?php
namespace App\Ingredient\Application\Query\Ingredient\GetIngredient;

use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;

#[IsAuthorized(SecurityRole::USER)]
readonly final class GetIngredientQuery
{
    public function __construct(
        public string $id
    ){}
}
