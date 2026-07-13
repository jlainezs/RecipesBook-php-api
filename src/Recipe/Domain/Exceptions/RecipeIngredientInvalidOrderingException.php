<?php

namespace App\Recipe\Domain\Exceptions;

use App\Shared\Domain\Exception\InvalidOrderingException;

class RecipeIngredientInvalidOrderingException extends InvalidOrderingException
{
    public function __construct() {
        parent::__construct('Recipe ingredient ordering is invalid');
    }
}
