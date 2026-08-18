<?php
namespace App\ShoppingList\Application\Query\ShoppingListInstance;

final readonly class ShoppingListInstanceResponse
{
    /**
     * @param ?ShoppingListDto $shoppingListDto
     */
    public function __construct(public ?ShoppingListDto $shoppingListDto)
    {}
}
