<?php
namespace App\ShoppingList\Application\Query\GetShoppingList;

final readonly class GetShoppingListQuery
{
    /**
     * @param string $id
     */
    public function __construct(
        #[Assert\Uuid]
        public string $id
    ){}
}
