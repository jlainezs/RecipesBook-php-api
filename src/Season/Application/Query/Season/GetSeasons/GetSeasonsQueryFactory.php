<?php

namespace App\Season\Application\Query\Season\GetSeasons;

class GetSeasonsQueryFactory
{
    public function createFromDto(GetSeasonsDto $dto): GetSeasonsQuery
    {
        return new GetSeasonsQuery($dto->page->offset, $dto->page->limit);
    }
}
