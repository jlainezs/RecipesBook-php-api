<?php
namespace App\Ingredient\Domain\Exceptions;

use App\Shared\Domain\Exception\EntityNotFoundException;

final class IngredientTypeNotFoundException extends EntityNotFoundException
{
    public function __construct(string $ingredientTypeId)
    {
        parent::__construct(sprintf('Ingredient type with id "%s" not found', $ingredientTypeId));
    }
}
