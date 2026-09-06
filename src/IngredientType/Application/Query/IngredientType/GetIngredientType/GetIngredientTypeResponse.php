<?php
namespace App\IngredientType\Application\Query\IngredientType\GetIngredientType;

use App\IngredientType\Application\Query\IngredientType\IngredientTypeDto;

final readonly class GetIngredientTypeResponse
{
    public function __construct(
        public ?IngredientTypeDto $ingredientType
    ){}
}
