<?php
namespace App\ShoppingList\Application\Query\GetSoppingLists;

class GetShoppingListsQueryFactory
{
    public function createFromDto(GetShoppingListsQueryDto $dto): GetShoppingListsQuery
    {
        return new GetShoppingListsQuery(
            $dto->offset, $dto->limit
        );
    }
}
