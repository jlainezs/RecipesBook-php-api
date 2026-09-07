<?php

namespace App\Recipe\Application\Query\Recipe\GetRecipe;

use App\Recipe\Application\Query\Recipe\RecipeDto;

final readonly class GetRecipeQueryResponse
{
    public function __construct(public ?RecipeDto $recipeDto)
    {}
}
