<?php
namespace App\Ingredient\Application\Command\Ingredient\CreateIngredient;

final readonly class CreateIngredientResponse
{
    public function __construct(
        public string $id,
    ){}
}
