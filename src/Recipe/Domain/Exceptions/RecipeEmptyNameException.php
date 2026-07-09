<?php
namespace App\Recipe\Domain\Exceptions;

use InvalidArgumentException;

final class RecipeEmptyNameException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Recipe name cannot be empty');
    }
}
