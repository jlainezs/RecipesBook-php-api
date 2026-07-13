<?php
namespace App\Recipe\Domain\Exceptions;

use InvalidArgumentException;

class RecipeIngredientInvalidQuantityException extends InvalidArgumentException
{
    public function __construct(){
        parent::__construct('Recipe ingredient quantity is invalid');
    }
}
