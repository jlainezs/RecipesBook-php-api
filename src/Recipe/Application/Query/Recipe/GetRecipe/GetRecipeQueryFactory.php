<?php
namespace App\Recipe\Application\Query\Recipe\GetRecipe;

readonly class GetRecipeQueryFactory
{
    public function createFromDto(GetRecipeDto $dto): GetRecipeQuery
    {
        return new GetRecipeQuery($dto->id);
    }
}
