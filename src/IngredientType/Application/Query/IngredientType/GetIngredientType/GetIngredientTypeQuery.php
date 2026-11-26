<?php
namespace App\IngredientType\Application\Query\IngredientType\GetIngredientType;


use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;

#[IsAuthorized(SecurityRole::USER)]
readonly final class GetIngredientTypeQuery
{
    public function __construct(
        public string $id
    ){}
}
