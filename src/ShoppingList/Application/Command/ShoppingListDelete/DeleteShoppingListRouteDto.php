<?php
namespace App\ShoppingList\Application\Command\ShoppingListDelete;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class DeleteShoppingListRouteDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $id
    ){}
}
