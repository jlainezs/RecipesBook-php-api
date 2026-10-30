<?php
namespace App\Season\Application\Query\Season\GetSeason;

class GetSeasonQueryFactory
{
    public function createFromDto(GetSeasonDto $dto): GetSeasonQuery
    {
        return new GetSeasonQuery($dto->id);
    }
}
