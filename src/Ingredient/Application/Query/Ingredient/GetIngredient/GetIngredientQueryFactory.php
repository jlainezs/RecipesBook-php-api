<?php
namespace App\Ingredient\Application\Query\Ingredient\GetIngredient;

class GetIngredientQueryFactory
{
    public function __construct()
    {}
    public function createFromDto(GetIngredientDto $dto): GetIngredientQuery
    {
        return new GetIngredientQuery($dto->id);
    }
}
