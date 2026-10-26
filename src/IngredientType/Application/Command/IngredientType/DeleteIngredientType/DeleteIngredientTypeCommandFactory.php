<?php
namespace App\IngredientType\Application\Command\IngredientType\DeleteIngredientType;

class DeleteIngredientTypeCommandFactory
{
    public function createFromDto(DeleteIngredientTypeDto $dto): DeleteIngredientTypeCommand
    {
        return new DeleteIngredientTypeCommand($dto->id);
    }
}
