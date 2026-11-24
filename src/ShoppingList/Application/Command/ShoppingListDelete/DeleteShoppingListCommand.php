<?php
namespace App\ShoppingList\Application\Command\ShoppingListDelete;

use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
final readonly class DeleteShoppingListCommand
{
    public function __construct(
        public string $id,
    ){}
}
