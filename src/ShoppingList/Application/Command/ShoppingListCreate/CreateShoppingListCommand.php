<?php
namespace App\ShoppingList\Application\Command\ShoppingListCreate;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateShoppingListCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
        #[Assert\NotBlank]
        public string $name,
        public iterable $items
    )
    {}
}
