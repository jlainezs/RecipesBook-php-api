<?php
namespace App\ShoppingList\Application\Command\ShoppingListUpdate;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateShoppingListRouteDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $id
    ){}
}
