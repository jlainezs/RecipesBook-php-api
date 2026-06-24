<?php

namespace App\IngredientType\Application\Command\IngredientType;

readonly final class IngredientTypeCreateCommand
{
    public function __construct(public readonly string $name)
    {}
}
