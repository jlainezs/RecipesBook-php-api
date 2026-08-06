<?php
namespace App\ShoppingList\Application\Command\ShoppingListUpdate;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class ShoppingListUpdateCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,

        #[Assert\NotBlank]
        public string $name,

    ){}
}
