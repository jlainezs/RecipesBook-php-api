<?php
namespace App\ShoppingList\Application\Query\GetShoppingList;

use App\ShoppingList\Application\Query\ShoppingListDto;

final readonly class ShoppingListResponse
{
    /**
     * @param ?ShoppingListDto $shoppingListDto
     */
    public function __construct(public ?ShoppingListDto $shoppingListDto)
    {}
}
