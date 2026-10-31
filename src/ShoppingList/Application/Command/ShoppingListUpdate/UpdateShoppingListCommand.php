<?php
namespace App\ShoppingList\Application\Command\ShoppingListUpdate;


final readonly class UpdateShoppingListCommand
{
    public function __construct(
        public string $id,
        public string $name,
        public iterable $items
    ){}
}
