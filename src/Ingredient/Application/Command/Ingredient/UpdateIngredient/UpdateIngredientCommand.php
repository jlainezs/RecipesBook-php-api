<?php
namespace App\Ingredient\Application\Command\Ingredient\UpdateIngredient;

final readonly class UpdateIngredientCommand
{
    public function __construct(
        public string $id,
        public string $name,
        public string $description,
        public string $ingredientTypeId
    ){}
}
