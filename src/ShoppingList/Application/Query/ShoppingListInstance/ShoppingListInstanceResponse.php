<?php
namespace App\ShoppingList\Application\Query\ShoppingListInstance;

final readonly class ShoppingListInstanceResponse
{
    public function __construct(public ?ShoppingListDto $shoppingListDto)
    {}
}
