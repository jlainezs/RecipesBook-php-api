<?php
namespace App\ShoppingList\Application\Command\ShoppingListCreate;

final readonly class CreateShoppingListResponse
{
    public function __construct(
        public string $id
    ){}
}
