<?php
namespace App\ShoppingList\Application\Command\ShoppingListUpdate;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class ShoppingListUpdateDto
{
    /**
     * @param string $name
     * @param iterable $items
     */
    public function __construct(
        #[Assert\NotBlank]
        public string $name,

        public iterable $items
    ){}
}
