<?php
namespace App\IngredientType\Domain\Exceptions;

use InvalidArgumentException;
use Throwable;

final class IngredientTypeEmptyNameException extends InvalidArgumentException
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
