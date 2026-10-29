<?php
namespace App\Recipe\Application\Command\RecipeDelete;

class DeleteRecipeCommandFactory
{
    public function createFromDto(DeleteRecipeDto $dto): DeleteRecipeCommand
    {
        return new DeleteRecipeCommand($dto->id);
    }
}
