<?php
namespace App\ShoppingList\Application\Query\ShoppingList;

final readonly class ShoppingListResponse
{
    /**
     * @param ?ShoppingListDto $shoppingListDto
     */
    public function __construct(public ?ShoppingListDto $shoppingListDto)
    {}
}
