<?php
namespace App\Ingredient\Application\Query\Ingredient\GetIngredient;

readonly final class GetIngredientQuery
{
    public function __construct(
        public string $id
    ){}
}
