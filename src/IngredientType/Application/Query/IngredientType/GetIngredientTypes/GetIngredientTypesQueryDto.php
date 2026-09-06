<?php
namespace App\IngredientType\Application\Query\IngredientType\GetIngredientTypes;

final readonly class GetIngredientTypesQueryDto
{
    public function __construct(
        public int $offset = 0,
        public int $limit = 20
    ){}
}
