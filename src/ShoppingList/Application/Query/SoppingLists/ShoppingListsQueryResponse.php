<?php
namespace App\ShoppingList\Application\Query\SoppingLists;

use App\ShoppingList\Application\Query\GetShoppingList\ShoppingListDto;

final readonly class ShoppingListsQueryResponse
{
    public function __construct(
        /**
         * @var ShoppingListDto[]
         */
        public array $items
    ) {}
}
