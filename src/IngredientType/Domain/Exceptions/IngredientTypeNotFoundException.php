<?php
namespace App\IngredientType\Domain\Exceptions;

use App\Shared\Domain\Exception\EntityNotFoundException;
use Throwable;

class IngredientTypeNotFoundException extends EntityNotFoundException
{
    public function __construct(readonly string $requestedId = "", int $code = 0, readonly ?Throwable $previous = null)
    {
        $message = sprintf('Ingredient type with id "%s" not found', $requestedId);
        parent::__construct($message, $code, $previous);
    }
}
