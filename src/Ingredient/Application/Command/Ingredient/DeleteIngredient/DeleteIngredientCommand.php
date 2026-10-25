<?php
namespace App\Ingredient\Application\Command\Ingredient\DeleteIngredient;

readonly final class DeleteIngredientCommand
{
    public function __construct(
        public string $id
    ){}
}
