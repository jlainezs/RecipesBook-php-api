<?php
namespace App\Ingredient\Application\Command\Ingredient;

readonly final class IngredientDeleteCommand
{
    public function __construct(public readonly string $id)
    {}
}
