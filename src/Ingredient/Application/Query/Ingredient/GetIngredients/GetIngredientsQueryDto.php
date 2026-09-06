<?php
namespace App\Ingredient\Application\Query\Ingredient\GetIngredients;

final readonly class GetIngredientsQueryDto
{
    public function __construct(
        public int $offset = 0,
        public int $limit = 20
    ){}
}
