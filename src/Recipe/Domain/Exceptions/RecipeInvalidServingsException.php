<?php
namespace App\Recipe\Domain\Exceptions;

use InvalidArgumentException;

class RecipeInvalidServingsException extends InvalidArgumentException
{
    public function __construct(int $requestedRating)
    {
        $message = sprintf("Invalid rating. Servings must be greater than 1. Required %s", $requestedRating);
        parent::__construct($message);
    }
}
