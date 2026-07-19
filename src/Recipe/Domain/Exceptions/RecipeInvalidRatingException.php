<?php
namespace App\Recipe\Domain\Exceptions;

use InvalidArgumentException;

class RecipeInvalidRatingException extends InvalidArgumentException
{
    public function __construct(int $requestedRating)
    {
        $message = sprintf("Invalid rating. Rating must be between 1 and 5. Required %s", $requestedRating);
        parent::__construct($message);
    }
}
