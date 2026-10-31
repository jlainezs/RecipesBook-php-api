<?php
namespace App\ShoppingList\Application\Query\GetShoppingList;

class GetShoppingListQueryFactory
{
    public function createFromDto(GetShoppingListQueryRouteDto $dto)
    {
        return new GetShoppingListQuery($dto->id);
    }
}
