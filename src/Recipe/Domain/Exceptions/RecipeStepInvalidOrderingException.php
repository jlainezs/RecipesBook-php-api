<?php
namespace App\Recipe\Domain\Exceptions;

use InvalidArgumentException;

final class RecipeStepInvalidOrderingException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Recipe step ordering must be greater than 0');
    }
}
