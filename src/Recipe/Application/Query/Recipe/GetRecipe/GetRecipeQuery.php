<?php
namespace App\Recipe\Application\Query\Recipe\GetRecipe;

final readonly class GetRecipeQuery
{
    public function __construct(
        public string $id
    ) {}
}
