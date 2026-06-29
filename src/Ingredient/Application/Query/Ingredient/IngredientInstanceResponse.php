<?php
namespace App\Ingredient\Application\Query\Ingredient;

final readonly class IngredientInstanceResponse
{
    public function __construct(public ?IngredientDto $ingredientDto)
    {}
}
