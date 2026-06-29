<?php
namespace App\Ingredient\Application\Command\Ingredient;

readonly final class IngredientCreateCommand
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly string $ingredientTypeId
    ){}
}
