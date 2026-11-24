<?php
namespace App\Ingredient\Application\Command\Ingredient\CreateIngredient;

use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
readonly final class CreateIngredientCommand
{
    public function __construct(
        public string $id,
        public string $name,
        public string $description,
        public string $ingredientTypeId
    ){}
}
