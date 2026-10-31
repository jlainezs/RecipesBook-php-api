<?php
namespace App\ShoppingList\Application\Query\GetSoppingLists;

use App\ShoppingList\Application\Query\GetShoppingList\ShoppingListDto;

final readonly class GetShoppingListsQueryResponse
{
    public function __construct(
        /**
         * @var ShoppingListDto[]
         */
        public array $items
    ) {}
}
