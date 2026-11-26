<?php
namespace App\ShoppingList\Application\Command\ShoppingListCreate;

use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;
use Symfony\Component\Validator\Constraints as Assert;

#[IsAuthorized(SecurityRole::USER)]
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
