<?php
namespace App\ShoppingList\Application\Command\ShoppingListCreate;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class ShoppingListCreateCommand
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,
    )
    {}
}
