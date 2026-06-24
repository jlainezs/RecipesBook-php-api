<?php

namespace App\IngredientType\Application\Command\IngredientType;

readonly final class IngredientTypeDeleteCommand
{
    public function __construct(public readonly string $id)
    {}
}
