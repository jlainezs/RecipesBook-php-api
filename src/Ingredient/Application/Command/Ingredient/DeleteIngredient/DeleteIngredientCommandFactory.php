<?php
namespace App\Ingredient\Application\Command\Ingredient\DeleteIngredient;

readonly class DeleteIngredientCommandFactory
{
    public function __construct(){}

    public function createFromDto(DeleteIngredientDto $dto): DeleteIngredientCommand
    {
        return new DeleteIngredientCommand($dto->id);
    }
}
