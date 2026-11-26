<?php
namespace App\ShoppingList\Application\Query\GetShoppingList;

use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;

#[IsAuthorized(SecurityRole::USER)]
final readonly class GetShoppingListQuery
{
    /**
     * @param string $id
     */
    public function __construct(
        public string $id
    ){}
}
