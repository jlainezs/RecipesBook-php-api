<?php
namespace App\Recipe\Application\Query\Recipe\GetRecipes;

class GetRecipesQueryFactory
{
    public function createFromDto(GetRecipesDto $dto): GetRecipesQuery
    {
        return new GetRecipesQuery($dto->offset, $dto->limit);
    }
}
