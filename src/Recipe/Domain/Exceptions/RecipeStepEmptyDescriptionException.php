<?php

namespace App\Recipe\Domain\Exceptions;

use InvalidArgumentException;

final class RecipeStepEmptyDescriptionException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Recipe step description cannot be empty');
    }
}
