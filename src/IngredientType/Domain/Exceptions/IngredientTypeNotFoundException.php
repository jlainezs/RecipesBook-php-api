<?php

namespace App\IngredientType\Domain\Exceptions;

use Exception;
use Throwable;

class IngredientTypeNotFoundException extends Exception
{
    public function __construct(string $requestedId = "", int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf('Ingredient type with id "%s" not found', $requestedId);
        parent::__construct($message, $code, $previous);
    }
}
