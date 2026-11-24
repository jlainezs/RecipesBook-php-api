<?php
namespace App\Ingredient\Application\Command\Ingredient\UpdateIngredient;

use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
final readonly class UpdateIngredientCommand
{
    public function __construct(
        public string $id,
        public string $name,
        public string $description,
        public string $ingredientTypeId
    ){}
}
