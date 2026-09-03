<?php
namespace App\Ingredient\Application\Command\Ingredient\IngredientCreate;

final readonly class CreateIngredientDto
{
    public function __construct(
        public string $name,
        public string $ingredientTypeId,
        public string $description,
    ){}
}
