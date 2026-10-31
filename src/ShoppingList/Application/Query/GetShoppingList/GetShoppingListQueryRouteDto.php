<?php
namespace App\ShoppingList\Application\Query\GetShoppingList;

use Symfony\Component\Validator\Constraints as Assert;
class GetShoppingListQueryRouteDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $id
    ){}
}
