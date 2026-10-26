<?php
namespace App\Ingredient\Application\Query\Ingredient\GetIngredients;

class GetIngredientsQueryFactory
{
    public function createFromDto(GetIngredientsQueryDto $dto): GetIngredientsQuery
    {
        return new GetIngredientsQuery(
            $dto->offset,
            $dto->limit
        );
    }
}
