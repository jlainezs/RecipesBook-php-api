<?php

namespace App\Season\Application\Query\Season;

final readonly class SeasonInstanceResponse
{
    public function __construct(
        public readonly ?SeasonDto $ingredientType
    )
    {}
}
