<?php
namespace App\IngredientType\Application\Query\IngredientType\GetIngredientTypes;

class GetIngredientTypesQueryFactory
{
    public function createFromDto(GetIngredientTypesDto $dto): GetIngredientTypesQuery
    {
        return new GetIngredientTypesQuery(
            $dto->page->offset,
            $dto->page->limit
        );
    }
}
