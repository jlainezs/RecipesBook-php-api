<?php

namespace App\Season\Application\Query\Season;

final readonly class SeasonsQueryResponse
{
    public function __construct(
        /**
         * @var SeasonDto[]
         */
        public readonly array $items,
    ) {}
}
