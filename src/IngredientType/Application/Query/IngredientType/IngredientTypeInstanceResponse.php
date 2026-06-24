<?php

namespace App\IngredientType\Application\Query\IngredientType;
final readonly class IngredientTypeInstanceResponse
{
    public function __construct(
        public readonly ?IngredientTypeDto $ingredientType
    )
    {}
}
