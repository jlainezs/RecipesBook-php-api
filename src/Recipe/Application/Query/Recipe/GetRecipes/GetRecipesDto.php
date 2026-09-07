<?php
namespace App\Recipe\Application\Query\Recipe\GetRecipes;

final readonly class GetRecipesDto
{
    public function __construct(
        public int $offset = 0,
        public int $limit = 10,
    ){}
}
