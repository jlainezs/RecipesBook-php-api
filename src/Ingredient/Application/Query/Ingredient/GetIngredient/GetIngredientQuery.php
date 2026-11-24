<?php
namespace App\Ingredient\Application\Query\Ingredient\GetIngredient;

use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
readonly final class GetIngredientQuery
{
    public function __construct(
        public string $id
    ){}
}
