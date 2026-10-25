<?php
namespace App\Ingredient\Application\Command\Ingredient\UpdateIngredient;

readonly class UpdateIngredientCommandFactory
{
    public function __construct(){}

    public function createFromRequest(UpdateIngredientRouteDto $dtoRoute, UpdateIngredientDto $dto): UpdateIngredientCommand
    {
        return new UpdateIngredientCommand(
            $dtoRoute->id,
            $dto->name,
            $dto->description,
            $dto->ingredientTypeId
        );
    }
}
