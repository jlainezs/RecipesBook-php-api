<?php
namespace App\Ingredient\Domain\Exceptions;

use Exception;
use Throwable;

final class IngredientEmptyNameException extends Exception
{
    public function __construct(readonly ?Throwable $previous = null)
    {
        parent::__construct("Ingredient name is empty", 0, $previous);
    }
}
