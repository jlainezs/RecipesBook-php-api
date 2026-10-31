<?php

namespace App\ShoppingList\Application\Command\ShoppingListUpdate;

class UpdateShoppingListCommandFactory
{
    public function createFromRequest(UpdateShoppingListRouteDto $routeDto, UpdateShoppingListDto $dto): UpdateShoppingListCommand
    {
        return new UpdateShoppingListCommand(
            $routeDto->id,
            $dto->name,
            $dto->items
        );
    }
}
