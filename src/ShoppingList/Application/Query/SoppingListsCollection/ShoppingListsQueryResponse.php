<?php
namespace App\ShoppingList\Application\Query\SoppingListsCollection;

final readonly class ShoppingListsQueryResponse
{
    public function __construct(
        /**
         * @var ShoppingListDto[]
         */
        public array $items
    ) {}
}
