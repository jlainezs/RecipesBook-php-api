<?php
namespace App\ShoppingList\Application\Query\GetShoppingList;

use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
final readonly class GetShoppingListQuery
{
    /**
     * @param string $id
     */
    public function __construct(
        public string $id
    ){}
}
