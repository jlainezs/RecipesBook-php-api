<?php
namespace App\ShoppingList\Application\Query\GetShoppingList;

final readonly class ShoppingListResponse
{
    /**
     * @param ?ShoppingListDto $shoppingListDto
     */
    public function __construct(public ?ShoppingListDto $shoppingListDto)
    {}
}
