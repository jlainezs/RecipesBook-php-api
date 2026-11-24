<?php
namespace App\IngredientType\Application\Command\IngredientType\DeleteIngredientType;

use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
readonly final class DeleteIngredientTypeCommand
{
    public function __construct(
        public  string $id
    ){}
}
