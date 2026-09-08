<?php
namespace App\Season\Application\Query\Season\GetSeason;

use App\Season\Application\Query\Season\SeasonDto;

final readonly class GetSeasonQueryResponse
{
    public function __construct(
        public readonly ?SeasonDto $season
    )
    {}
}
