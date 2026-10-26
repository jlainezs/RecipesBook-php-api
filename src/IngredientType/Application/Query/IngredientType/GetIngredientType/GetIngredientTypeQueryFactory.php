<?php
namespace App\IngredientType\Application\Query\IngredientType\GetIngredientType;

class GetIngredientTypeQueryFactory
{
    public function createFromDto(GetIngredientTypeDto $dto): GetIngredientTypeQuery
    {
        return new GetIngredientTypeQuery($dto->id);
    }
}
