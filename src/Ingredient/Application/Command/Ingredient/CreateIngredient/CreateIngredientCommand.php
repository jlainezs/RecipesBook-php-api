<?php
namespace App\Ingredient\Application\Command\Ingredient\CreateIngredient;

use App\Security\Domain\Model\UserRole;
use App\Shared\Application\Security\IsAuthorized;

#[IsAuthorized(UserRole::ADMIN, UserRole::MANAGER, UserRole::USER)]
readonly final class CreateIngredientCommand
{
    public function __construct(
        public string $id,
        public string $name,
        public string $description,
        public string $ingredientTypeId
    ){}
}
