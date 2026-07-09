<?php

namespace App\Recipe\Domain\Exceptions;

use App\Shared\Domain\Exception\EntityNotFoundException;
use Throwable;

class RecipeNotFoundException extends EntityNotFoundException
{
    public function __construct(readonly string $requiredId, readonly ?Throwable $previous = null)
    {
        parent::__construct(
            sprintf("Recipe %s not found.", $requiredId),
            0,
            $previous
        );
    }
}
