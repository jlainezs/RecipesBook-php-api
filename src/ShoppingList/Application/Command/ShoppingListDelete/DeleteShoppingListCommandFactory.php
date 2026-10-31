<?php
namespace App\ShoppingList\Application\Command\ShoppingListDelete;

class DeleteShoppingListCommandFactory
{
    public function createFromDto(DeleteShoppingListRouteDto $dto): DeleteShoppingListCommand
    {
        return new DeleteShoppingListCommand($dto->id);
    }
}
