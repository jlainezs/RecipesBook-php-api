<?php
namespace App\IngredientType\Domain\Exceptions;

use Exception;
use Throwable;

final class IngredientTypeEmptyNameException extends Exception
{
    public function __construct(readonly ?Throwable $previous = null)
    {
        parent::__construct(
            "IngredientType name is empty",
            0,
            $previous
        );
    }
}
