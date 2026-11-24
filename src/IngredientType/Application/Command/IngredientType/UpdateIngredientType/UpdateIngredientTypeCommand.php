<?php
namespace App\IngredientType\Application\Command\IngredientType\UpdateIngredientType;

use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
final readonly class UpdateIngredientTypeCommand
{
    public function __construct(
        public string $id,
        public string $name
    ){}
}
