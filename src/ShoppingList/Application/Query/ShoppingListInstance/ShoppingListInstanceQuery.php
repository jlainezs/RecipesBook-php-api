<?php
namespace App\ShoppingList\Application\Query\ShoppingListInstance;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class ShoppingListInstanceQuery
{
    public function __construct(
        #[Assert\Uuid]
        public string $id
    ){}
}
