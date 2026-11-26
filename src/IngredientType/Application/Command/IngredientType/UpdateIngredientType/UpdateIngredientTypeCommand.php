<?php
namespace App\IngredientType\Application\Command\IngredientType\UpdateIngredientType;

use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;

#[IsAuthorized(SecurityRole::USER)]
final readonly class UpdateIngredientTypeCommand
{
    public function __construct(
        public string $id,
        public string $name
    ){}
}
