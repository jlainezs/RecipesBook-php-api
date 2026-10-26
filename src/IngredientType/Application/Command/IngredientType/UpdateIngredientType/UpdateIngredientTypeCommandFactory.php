<?php
namespace App\IngredientType\Application\Command\IngredientType\UpdateIngredientType;

class UpdateIngredientTypeCommandFactory
{
    public function createFromRequest(UpdateIngredientTypeRouteDto $routeDto, UpdateIngredientTypeDto $dto): UpdateIngredientTypeCommand
    {
        return new UpdateIngredientTypeCommand(
            $routeDto->id,
            $dto->name
        );
    }
}
