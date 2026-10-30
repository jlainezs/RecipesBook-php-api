<?php
namespace App\Season\Application\Query\Season\GetSeason;



final readonly class GetSeasonQuery
{
    public function __construct(
        public string $id
    ){}
}
