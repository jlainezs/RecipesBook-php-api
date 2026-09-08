<?php
namespace App\ShoppingList\Application\Query\SoppingLists;

final readonly class ShoppingListsDto
{
    public function __construct(
        public int $offset = 0,
        public int $limit = 20
    ){}
}
