<?php
namespace App\IngredientType\Application\Query\IngredientType;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class IngredientTypeInstanceResponse
{
    public function __construct(
        public ?IngredientTypeDto $ingredientType
    ){}
}
