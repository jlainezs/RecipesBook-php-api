<?php
namespace App\IngredientType\Application\Command\IngredientType\UpdateIngredientType;
final readonly class UpdateIngredientTypeDto
{
    public function __construct(
        public string $name,
    ) {}
}
