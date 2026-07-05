<?php
namespace App\Ingredient\Domain\Exceptions;

use App\Shared\Domain\Exception\EntityNotFoundException;
use Throwable;

final class IngredientNotFoundException extends EntityNotFoundException
{
    public function __construct(readonly string $requiredId, readonly ?Throwable $previous = null)
    {
        parent::__construct(
            sprintf("Ingredient %s not found.", $requiredId),
            0,
            $previous
        );
    }
}
