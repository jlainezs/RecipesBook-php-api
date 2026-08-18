<?php
namespace App\ShoppingList\Application\Query\ShoppingListInstance;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class ShoppingListInstanceQuery
{
    /**
     * @param string $id
     */
    public function __construct(
        #[Assert\Uuid]
        public string $id
    ){}
}
