<?php
namespace App\Ingredient\Domain\Exceptions;

use InvalidArgumentException;
use Throwable;

final class IngredientEmptyNameException extends InvalidArgumentException
{
    public function __construct(readonly ?Throwable $previous = null)
    {
        parent::__construct("Ingredient name is empty", 0, $previous);
    }
}
