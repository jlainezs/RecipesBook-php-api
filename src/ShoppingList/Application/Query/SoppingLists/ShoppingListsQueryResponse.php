<?php
namespace App\ShoppingList\Application\Query\SoppingLists;

use App\ShoppingList\Application\Query\ShoppingList\ShoppingListDto;

final readonly class ShoppingListsQueryResponse
{
    public function __construct(
        /**
         * @var ShoppingListDto[]
         */
        public array $items
    ) {}
}
