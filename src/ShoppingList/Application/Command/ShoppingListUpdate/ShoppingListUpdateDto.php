<?php
namespace App\ShoppingList\Application\Command\ShoppingListUpdate;

use Symfony\Component\Validator\Constraints as Assert;
final readonly class ShoppingListUpdateDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,
    ){}
}
