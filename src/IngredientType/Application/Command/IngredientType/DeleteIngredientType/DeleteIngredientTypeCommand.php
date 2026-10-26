<?php
namespace App\IngredientType\Application\Command\IngredientType\DeleteIngredientType;

readonly final class DeleteIngredientTypeCommand
{
    public function __construct(
        public  string $id
    ){}
}
