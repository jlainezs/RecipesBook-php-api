<?php
namespace App\Ingredient\Application\Query\Ingredient\GetIngredient;

use App\Ingredient\Application\Query\Ingredient\IngredientDto;

final readonly class GetIngredientResponse
{
    public function __construct(public ?IngredientDto $ingredientDto)
    {}
}
