<?php

namespace App\IngredientType\Application\Command\IngredientType\CreateIngredientType;

final readonly class CreateIngredientTypeDto
{
    public function __construct(public string $name)
    {
    }
}
