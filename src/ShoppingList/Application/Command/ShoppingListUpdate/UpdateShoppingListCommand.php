<?php
namespace App\ShoppingList\Application\Command\ShoppingListUpdate;


use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
final readonly class UpdateShoppingListCommand
{
    public function __construct(
        public string $id,
        public string $name,
        public iterable $items
    ){}
}
