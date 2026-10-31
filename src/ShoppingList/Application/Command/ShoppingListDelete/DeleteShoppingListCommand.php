<?php
namespace App\ShoppingList\Application\Command\ShoppingListDelete;


final readonly class DeleteShoppingListCommand
{
    public function __construct(
        public string $id,
    ){}
}
