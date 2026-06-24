<?php

namespace App\IngredientType\Domain\Exceptions;

use Exception;
use Throwable;

class IngredientTypeEmptyNameException extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            "IngredientType name is empty",
            0,
            $previous
        );
    }
}
