<?php
namespace App\Season\Application\Query\Season\GetSeasons;

final readonly class GetSeasonsDto
{
    public function __construct(
        public int $offset = 0,
        public int $limit = 10,
    ){}
}
