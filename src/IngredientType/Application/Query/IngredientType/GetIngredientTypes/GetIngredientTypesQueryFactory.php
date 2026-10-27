<?php
namespace App\IngredientType\Application\Query\IngredientType\GetIngredientTypes;

class GetIngredientTypesQueryFactory
{
    public function createFromDto(GetIngredientTypesDto $dto): GetIngredientTypesQuery
    {
        return new GetIngredientTypesQuery(
            $dto->offset,
            $dto->limit
        );
    }
}
