<?php
namespace App\Recipe\Domain\Exceptions;

use InvalidArgumentException;

class RecipeInvalidRatingException extends InvalidArgumentException
{
    public function __construct(int $requestedServings)
    {
        $message = sprintf("Invalid rating. Rating must be between 1 and 5. Required %s", $requestedServings);
        parent::__construct($message);
    }
}
