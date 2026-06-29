<?php

namespace App\Ingredient\Domain\Exceptions;

use Exception;
use Throwable;

final class IngredientNotFoundException extends Exception
{
    public function __construct(readonly string $requiredId, ?Throwable $previous = null)
    {
        parent::__construct(
            sprintf("Ingredient %s not found.", $requiredId),
            0,
            $previous
        );
    }
}
