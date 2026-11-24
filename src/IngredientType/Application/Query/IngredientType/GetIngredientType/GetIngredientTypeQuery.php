<?php
namespace App\IngredientType\Application\Query\IngredientType\GetIngredientType;


use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
readonly final class GetIngredientTypeQuery
{
    public function __construct(
        public string $id
    ){}
}
