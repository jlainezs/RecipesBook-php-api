<?php

namespace App\Season\Application\Query\Season\GetSeasons;

use App\Season\Application\Query\Season\SeasonDto;

final readonly class GetSeasonsQueryResponse
{
    public function __construct(
        /**
         * @var SeasonDto[]
         */
        public array $items,
    ) {}
}
