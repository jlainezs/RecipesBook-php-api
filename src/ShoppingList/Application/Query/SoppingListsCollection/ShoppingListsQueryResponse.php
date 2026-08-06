<?php
namespace App\ShoppingList\Application\Query\SoppingListsCollection;

use App\ShoppingList\Application\Query\ShoppingListInstance\ShoppingListDto;

final readonly class ShoppingListsQueryResponse
{
    public function __construct(
        /**
         * @var ShoppingListDto[]
         */
        public array $items
    ) {}
}
