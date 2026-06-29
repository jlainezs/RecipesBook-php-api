<?php
namespace App\Ingredient\Application\Command\Ingredient;

final readonly class IngredientUpdateCommand
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $description,
        public readonly string $ingredientTypeId
    ){}
}
