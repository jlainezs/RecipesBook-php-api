<?php
namespace App\IngredientType\Domain\Exceptions;

use App\Shared\Domain\Exceptions\EntityNotFoundException;
use Throwable;

class IngredientTypeNotFoundException extends EntityNotFoundException
{
    public function __construct(readonly string $userIdentifier = "", int $code = 0, readonly ?Throwable $previous = null)
    {
        $message = sprintf('Ingredient type with id "%s" not found', $userIdentifier);
        parent::__construct($message, $code, $previous);
    }
}
