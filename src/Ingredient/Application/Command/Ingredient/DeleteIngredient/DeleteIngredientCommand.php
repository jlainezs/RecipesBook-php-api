<?php
namespace App\Ingredient\Application\Command\Ingredient\DeleteIngredient;

use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
readonly final class DeleteIngredientCommand
{
    public function __construct(
        public string $id
    ){}
}
