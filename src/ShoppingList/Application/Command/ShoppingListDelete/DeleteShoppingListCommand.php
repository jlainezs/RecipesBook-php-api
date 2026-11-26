<?php
namespace App\ShoppingList\Application\Command\ShoppingListDelete;

use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;

#[IsAuthorized(SecurityRole::USER)]
final readonly class DeleteShoppingListCommand
{
    public function __construct(
        public string $id,
    ){}
}
