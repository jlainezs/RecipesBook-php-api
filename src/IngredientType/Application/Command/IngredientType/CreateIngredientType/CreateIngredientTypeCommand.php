<?php
namespace App\IngredientType\Application\Command\IngredientType\CreateIngredientType;

use App\Security\Domain\Model\UserRole;
use App\Shared\Application\Security\IsAuthorized;
use Symfony\Component\Validator\Constraints as Assert;

#[IsAuthorized(UserRole::ADMIN, UserRole::MANAGER, UserRole::USER)]
readonly final class CreateIngredientTypeCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
        #[Assert\NotBlank]
        public string $name
    )
    {}
}
