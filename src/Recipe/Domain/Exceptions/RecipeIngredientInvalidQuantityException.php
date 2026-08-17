<?php
namespace App\Recipe\Domain\Exceptions;

use InvalidArgumentException;

class RecipeIngredientInvalidQuantityException extends InvalidArgumentException
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null){
        parent::__construct(
            empty($message)
                ? 'Recipe ingredient quantity is invalid'
                : $message,
            $code,
            $previous
        );
    }
}
