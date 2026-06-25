<?php

namespace App\IngredientType\Application\Command\IngredientType;
final readonly class IngredientTypeUpdateCommand
{
    public function __construct(public readonly string $id, public readonly string $name)
    {}
}
