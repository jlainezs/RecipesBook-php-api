<?php
namespace App\ShoppingList\Application\Command\ShoppingListDelete;

use Symfony\Component\Validator\Constraints as Assert;
final readonly class ShoppingListDeleteCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
    ){}
}
