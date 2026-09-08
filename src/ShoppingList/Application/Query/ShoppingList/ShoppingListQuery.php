<?php
namespace App\ShoppingList\Application\Query\ShoppingList;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class ShoppingListQuery
{
    /**
     * @param string $id
     */
    public function __construct(
        #[Assert\Uuid]
        public string $id
    ){}
}
