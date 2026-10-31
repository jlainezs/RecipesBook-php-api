<?php
namespace App\ShoppingList\Application\Query\GetShoppingList;

final readonly class GetShoppingListQuery
{
    /**
     * @param string $id
     */
    public function __construct(
        public string $id
    ){}
}
