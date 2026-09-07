<?php
namespace App\Recipe\Application\Query\Recipe\GetRecipes;

use App\Recipe\Application\Query\Recipe\RecipeDto;

final readonly class GetRecipesQueryResponse
{
    public function __construct(
        /**
         * @var RecipeDto[]
         */
        public array $items
    ){}
}
