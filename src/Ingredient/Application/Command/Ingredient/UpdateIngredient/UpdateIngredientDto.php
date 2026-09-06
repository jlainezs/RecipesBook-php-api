<?php
namespace App\Ingredient\Application\Command\Ingredient\UpdateIngredient;
final readonly class UpdateIngredientDto
{
    public function __construct(
        public string $name,
        public string $description,
        public string $ingredientTypeId
    ){}
}
