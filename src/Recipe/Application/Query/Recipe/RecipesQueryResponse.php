<?php
namespace App\Recipe\Application\Query\Recipe;

final readonly class RecipesQueryResponse
{
    public function __construct(
        /**
         * @var RecipeDto[]
         */
        public array $items
    ){}
}
