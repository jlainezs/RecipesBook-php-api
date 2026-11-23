<?php
namespace App\Ingredient\Application\Command\Ingredient\CreateIngredient;

readonly final class CreateIngredientCommand
{
    public function __construct(
        public string $id,
        public string $name,
        public string $description,
        public string $ingredientTypeId
    ){}
}
