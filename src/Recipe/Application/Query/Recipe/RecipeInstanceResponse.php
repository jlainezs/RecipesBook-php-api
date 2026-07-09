<?php

namespace App\Recipe\Application\Query\Recipe;

final readonly class RecipeInstanceResponse
{
    public function __construct(public ?RecipeDto $recipeDto)
    {}
}
