<?php
namespace App\Recipe\Domain\Exceptions;

use Exception;

class RecipeInvalidServingsException extends Exception
{
    public function __construct(int $requestedServings)
    {
        $message = sprintf("Invalid rating. Servings must be greater than 1. Required %s", $requestedServings);
        parent::__construct($message);
    }
}
